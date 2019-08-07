<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositorys\{SanitationsRepository, ImportationsRepository, SettingsRepository};
use App\{Banks, Actions};

class SanitationProcessCommand extends Command
{
    /**
     * O nome do comando no console.
     *
     * @var string
     */
    protected $signature = "sanitation-process";

    /**
     * A descricão do comando no console.
     *
     * @var string
     */
    protected $description = "Processa a higienização em seus determinados lugares";

    /**
     * Id da importação
     */
    private $importationId;

    /**
     * Id da higienizacao
     */
    private $sanitationId;

    /**
     * Executa o comando no console.
     *
     * @return mixed
     */
    public function handle(SanitationsRepository $sanitationRepository, ImportationsRepository $importationRepository)
    {    
        
        # Busca as importações que estão aguardando serem processadas
        $sanitations = $sanitationRepository->findAll([
            'conditions' => 'status = :status',
            'bind' => [
                'status' => 'A'
            ]
        ]);
        
        if (!empty($sanitations)) {

            foreach ($sanitations as $sanitation) {

                # Indica que a higienizacao iniciou
                DB::statement("UPDATE sanitations SET status = ? WHERE id = ?", ['H', $sanitation->id]);

                $this->importationId = $sanitation->importation_id;
                $this->sanitationId  = $sanitation->id;

                $this->warn("Iniciando processamento de higienização da importação de id: {$sanitation->importation_id}");

                # Identifica qual banco e action foi utilizado
                $this->warn("Identificando banco e ação...");
                $importation = $importationRepository->getById($sanitation->importation_id, false);
                $nameBank   = Banks::find($importation['bank_id'])->get()->toArray()[0]['name'];
                $actionName = Actions::find($importation['action_id'])->get()->toArray()[0]['name'];
                
                if (empty($nameBank) || empty($actionName))
                    $this->error("Não foi possivel identificar o banco e ação!");    
                else {
                    $this->info("Itens identificados! Banco '$nameBank' e ação '$actionName'");
                    $this->warn("Iniciando higienização!");

                    switch ($nameBank) {
                        case 'BMG' :

                            if ($actionName == 'Cadastro (Saque)')
                                $this->_sanitizeWithdrawBMG();

                        break;
                    }

                }

                # Indica que a higienizacao finalizou
                $this->info("Higienização finalizada!");
                DB::statement("UPDATE sanitations SET status = ? WHERE id = ?", ['F', $sanitation->id]);

                # Cria a campanha
                DB::insert("INSERT INTO campaigns (sanitation_id, type_campaign_id, created_at) VALUES (?, ?, ?)", [$sanitation->id, 1, date('Y-m-d H:i:s')]);
                $this->info("Campanha criada!");

            }

        } else
            $this->error("Nenhuma higienização esperando para ser iniciada!");

    }

    /**
     * Inicia o processo de higienização do Saque BMG
     */
    private function _sanitizeWithdrawBMG() : void
    {
        $offset = 0;
        $limit  = 2;        
        $lote   = 0;
        $arrLotesCpf = [];

        $this->warn('Identificando a quantidade total de clientes para higienizar...');
        $countClients = DB::select("SELECT COUNT(*) AS qtd FROM importation_{$this->importationId}_clients")[0]->qtd;
        $this->info('Quantidade identificada! Total: ' . $countClients);

        $this->warn("Criando lotes de $limit...");

        # Busca os dados de acesso a API do BMG
        $dataAccessBmgApi = SettingsRepository::get('acesso_api_bmg');

        # Cria os lotes de cliente de 20 em 20
        do {

            # Busca os clientes da importação de 20 em 20
            $clients = DB::select("SELECT 
                                    IC.id, 
                                    IC.cpf, 
                                    ICB.entity_code 
                                FROM 
                                    importation_{$this->importationId}_clients AS IC 
                                INNER JOIN 
                                    importation_{$this->importationId}_client_bmgs AS ICB ON ICB.client_id = IC.id 
                                LIMIT $offset,$limit");

            # Separa os clientes por lote
            foreach ($clients as $client) {
                $arrLotesCpf[$lote][] = ['cpf' => $client->cpf, 'id' => $client->id, 'entityCode' => $client->entity_code];
            }

            # Incrementa o offset para o novo ser enviado
            $offset += $limit;
            $lote++;

        } while ($offset < $countClients);
        $this->info('Lotes criados!');

        # Inicia o multi curl
        $this->warn('Iniciando multicurl...');
        foreach ($arrLotesCpf as $keyLote => $loteCpf) {

            $this->warn('Iniciando o processamento do lote ' . $keyLote);

            # Busca cartão do cliente
            $arrReturn = $this->_sanitizeWithdrawBMGSearchCard($loteCpf, $dataAccessBmgApi);

            # Busca o limite do cartão
            $arrReturn = $this->_sanitizeWithdrawBMGSearchCardLimit($arrReturn, $dataAccessBmgApi);

            # Registra o log
            $this->_registerLot($arrReturn);

            # Salva dados importantes capturados da higienização
            foreach ($arrReturn as $data) {
                
                if ($data['erro'] && !isset($data['liberado']))
                    continue;

                # Atualiza o numero interno da conta
                $this->info("Atualizando numero interno da conta...");
                DB::statement("UPDATE importation_{$this->importationId}_client_bmg_withdraws SET internal_account_number = ? WHERE client_id = ?", [
                    $data['numeroContaInterna'],
                    $data['client_id']
                ]);

                # Insere a matricula do cliente
                $this->info("Inserindo matricula do cliente...");
                DB::insert("INSERT INTO importation_{$this->importationId}_client_registrations (client_id, registration) VALUES (?,?)", [
                    $data['client_id'],
                    $data['matricula']
                ]);

                # Insere o valor do saque
                $this->info("Atualizando valor do saque...");
                if (isset($data['msg']) && empty($data['msg']) && $data['liberado']) {
                    DB::statement("UPDATE importation_{$this->importationId}_client_bmg_withdraws SET value = ? WHERE client_id = ?", [
                        $data['valorSaqueMaximo'],
                        $data['client_id']
                    ]);
                }

            }

        }   

    }

    /**
     * Multi curl para consulta de cartão no BGM
     * 
     * @return array
     */
    private function _sanitizeWithdrawBMGSearchCard(array $loteCpf, array $dataAccessBmgApi) : array
    {

        $this->warn('Buscando cartões dos clientes...');

        $mlc      = curl_multi_init();
        $requests = [];
        foreach ($loteCpf as $key => $data) {
                
            $this->warn('Montando os dados de envio:');
            $arrPost = [
                'usuario'        => $dataAccessBmgApi['api_bmg_user'],
                'senha'          => $dataAccessBmgApi['api_bmg_password'],
                'cpf'            => $data['cpf'],
                'codigoEntidade' => $data['entityCode']
            ];
            $this->warn(json_encode($arrPost));
            
            # Monta os parametros da requisição
            $requests[$key]                = array();
            // $requests[$key]['url']         = "http://159.89.41.5/bmg/consultaCartao.php";
            $requests[$key]['url']         = "http://ferramentas.webcredbr.com.br/wsbmg/consultaCartao.php";
            $requests[$key]['curl_handle'] = curl_init($requests[$key]['url']);
            $requests[$key]['cpf']         = $data['cpf'];
            $requests[$key]['client_id']   = $data['id'];
            curl_setopt($requests[$key]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($requests[$key]['curl_handle'], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($requests[$key]['curl_handle'], CURLOPT_POST, true);
            curl_setopt($requests[$key]['curl_handle'], CURLOPT_POSTFIELDS, $arrPost);
            curl_multi_add_handle($mlc, $requests[$key]['curl_handle']);

        }

        # Executa todos os requests usando o curl_multi_exec e aguarda o termino de todos
        $stillRunning = false;
        do {
            curl_multi_exec($mlc, $stillRunning);
        } while ($stillRunning);

        # Varre os requests executados
        $arrReturn = [];
        foreach($requests as $key => $request){

            # Remove o handler do request atual
            curl_multi_remove_handle($mlc, $request['curl_handle']);

            # Captura as respostas
            $requests[$key]['content']   = curl_multi_getcontent($request['curl_handle']);
            $requests[$key]['http_code'] = curl_getinfo($request['curl_handle'], CURLINFO_HTTP_CODE);
            $data                        = json_decode($requests[$key]['content'], true);
            $data['client_id']           = $requests[$key]['client_id'];
            $data['cpf']                 = $requests[$key]['cpf'];
            $arrReturn[]                 = $data;
            
        }
        
        return $arrReturn;

    }

    /**
     * Multi curl para consulta de limite cartão no BGM
     * 
     * @return array
     */
    private function _sanitizeWithdrawBMGSearchCardLimit(array $arrReturnCard, array $dataAccessBmgApi) : array
    {
        $this->warn('Buscando limite dos cartões dos clientes...');
        
        $mlc       = curl_multi_init();
        $requests  = [];
        $arrReturn = [];
        $count     = 0;
        
        foreach ($arrReturnCard as $key => $clientCard) {
            
            if (!$clientCard['erro']) {

                foreach ($clientCard['cartoes'] as $cartao) {
                    
                    # Verifica se o cliente possui cartão
                    $this->warn("Verificando se cliente {$clientCard['client_id']} possui cartão liberado...");
                    if ($cartao['liberado']) {
                    
                        $this->info("Cartão encontrado para o cliente {$clientCard['client_id']}! Buscando limite...");
                        $this->warn('Montando os dados de envio:');
                        $arrPost = [
                            'usuario'            => $dataAccessBmgApi['api_bmg_user'],
                            'senha'              => $dataAccessBmgApi['api_bmg_password'],
                            'cpf'                => $clientCard['cpf'],
                            'codigoEntidade'     => $cartao['entidade'],
                            'matricula'          => $cartao['matricula'],
                            'numeroContaInterna' => $cartao['numeroContaInterna']
                        ];
                        $this->warn(json_encode($arrPost));

                        # Monta os parametros da requisição
                        $requests[$count]                            = array();
                        // $requests[$count]['url']                     = "http://159.89.41.5/bmg/consultaLimiteCartao.php";
                        $requests[$count]['url']                     = "http://ferramentas.webcredbr.com.br/wsbmg/consultaLimiteCartao.php";
                        $requests[$count]['curl_handle']             = curl_init($requests[$count]['url']);
                        $requests[$count]['paramsCard']              = $cartao;
                        $requests[$count]['paramsCard']['client_id'] = $clientCard['client_id'];
                        curl_setopt($requests[$count]['curl_handle'], CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($requests[$count]['curl_handle'], CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($requests[$count]['curl_handle'], CURLOPT_POST, true);
                        curl_setopt($requests[$count]['curl_handle'], CURLOPT_POSTFIELDS, $arrPost);
                        curl_multi_add_handle($mlc, $requests[$count]['curl_handle']);
                        
                        $count++;

                    } else {

                        # Monta os dados para a higienização
                        $this->error("Cliente {$clientCard['client_id']} NAO possui cartão liberado!");
                        $cartao['client_id'] = $clientCard['client_id'];
                        $cartao['msg']       = $cartao['mensagemImpedimento'];
                        $cartao['erro']      = true;
                        $arrReturn[]         = $cartao;
                    }

                }

            } else {
                
                # Monta os dados para a higienização
                $this->error("Cliente {$clientCard['client_id']} sem cartão!");
                $arrReturn[] = $clientCard;
            }
            

        }

        # Executa todos os requests usando o curl_multi_exec e aguarda o termino de todos
        $stillRunning = false;
        do {
            curl_multi_exec($mlc, $stillRunning);
        } while ($stillRunning);

        # Busca o modelo de envio para sms saque
        $smsModel = SettingsRepository::get('modelo_envio_sms_saque');

        # Varre os requests executados
        foreach($requests as $key => $request) {

            # Remove o handler do request atual
            curl_multi_remove_handle($mlc, $request['curl_handle']);

            # Captura as respostas
            $requests[$key]['content']   = curl_multi_getcontent($request['curl_handle']);
            $requests[$key]['http_code'] = curl_getinfo($request['curl_handle'], CURLINFO_HTTP_CODE);
            
            # Monta o retorno
            $paramsCard    = $requests[$key]['paramsCard'];
            $return        = json_decode($requests[$key]['content'], true);
            $return['msg'] = $return['mensagemDeErro'];
            if (!empty($return['msg']))
                $return['erro'] = true;
            else
                $return['erro'] = false;

            # Adiciona a mensagem modelo, substituindo os parametros
            $valorSaque           = number_format($return['valorSaqueMaximo'], 2, ',', '.');
            $return['obsSuccess'] = str_replace('{valorSaque}', $valorSaque, $smsModel['modelo_envio_sms_saque']);

            $arrReturn[] = array_merge($paramsCard, $return);
        }

        return $arrReturn;
    }

    /**
     * Registra os itens do lote
     * 
     * @return void
     */
    private function _registerLot(array $arrData)
    {
        
        $this->warn("Iniciando higienização...");
        foreach ($arrData as $clientData) {

            # Classifica os clientes
            if ($clientData['erro']) {
                $obs    = $clientData['msg'];
                $status = 'E';
                $field  = 'total_fail';
                $this->error($obs);
            } else {
                $status = 'S';
                $obs    = $clientData['obsSuccess'];
                $field  = 'total_success';
                $this->info($obs);
            }

            $this->warn("Classificando cliente {$clientData['client_id']}...");
            DB::insert("INSERT INTO sanitation_lots (importation_id, client_id, status, obs) VALUES (?,?,?,?)", [
                $this->importationId,
                $clientData['client_id'],
                $status,
                $obs
            ]);

            # Aumenta a quantidade de itens higienizados
            DB::statement("UPDATE sanitations SET $field = $field + 1 WHERE id = ?", [$this->sanitationId]);
        }
        

    }

}