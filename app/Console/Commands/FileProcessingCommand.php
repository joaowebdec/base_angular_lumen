<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositorys\ImportationsRepository;
use App\{Banks, Actions};
use App\Services\MoneyService;

class FileProcessingCommand extends Command
{
    /**
     * O nome do comando no console.
     *
     * @var string
     */
    protected $signature = "processing-imports";

    /**
     * A descricão do comando no console.
     *
     * @var string
     */
    protected $description = "Processa/Importa o arquivo que foi feito upload e já cria o lote de higienização";

    /**
     * Id da importação
     */
    private $importationId;

    /**
     * Executa o comando no console.
     *
     * @return mixed
     */
    public function handle(ImportationsRepository $importationRepository)
    {    
        
        # Busca as importações que estão aguardando serem processadas
        $importations = $importationRepository->findAll([
            'conditions' => 'status = :status',
            'bind' => [
                'status' => 'A'
            ]
        ]);
        
        if (!empty($importations)) {

            foreach ($importations as $importation) {

                $this->importationId = $importation->id;

                $this->warn("Iniciando processamento da importação: {$importation->description}");
                $this->warn("Identificando arquivo: {$importation->file_name}");

                # Identifica o arquivo da importação
                if (file_exists(__DIR__ . "/../../../public/imports/$importation->file_name")) {

                    $this->info("Arquivo encontrado! Iniciando processamento!");
                    $arrData = $this->_readFile(__DIR__ . "/../../../public/imports/$importation->file_name");

                    # Identifica qual banco e action foi utilizado
                    $this->warn("Identificando banco e ação...");
                    $nameBank   = Banks::find($importation->bank_id)->get()->toArray()[0]['name'];
                    $actionName = Actions::find($importation->action_id)->get()->toArray()[0]['name'];

                    if (empty($nameBank) || empty($actionName))
                        $this->error("Não foi possivel identificar o banco e ação!");    
                    else {
                        $this->info("Itens identificados! Banco '$nameBank' e ação '$actionName'");
                        $this->warn("Iniciando importação!");

                        # Inicia a importação
                        $this->_import($arrData, $importation->mapping, $nameBank, $actionName);

                    }

                } else
                    $this->error("Arquivo não foi encontrado! Processo abortado!");

            }

        } else
            $this->error("Nenhuma importação esperando para ser processada!");


    }

    /**
     * Importa o arquivo no banco de dados
     * 
     * @return array
     */
    private function _readFile(string $filePath) : array
    {
        $file    = fopen($filePath, 'r');
        $arrData = [];

        if (!$file)
            $this->error("Não foi possivel processar o arquivo!");
        else {
            $this->info("Processando arquivo...");

            # Identificar se o csv esta separado por , ou por ;
            $delimiter = ',';
            if (count(explode('CPF;', file_get_contents($filePath))) >= 2) {
                $delimiter = ';';
                $this->warn("Delimitador do arquivo: ';'");
            } else
                $this->warn("Delimitador do arquivo: ','");

            # Le a primeira linha para remover do lopping
            fgetcsv($file, 1000, $delimiter);

            # Le linha por linha
            $arrData = [];
            while ($row = fgetcsv($file, 1000, $delimiter)) {
                $arrData[] = $row;
            }
        
        }

        return $arrData;

    }

    /**
     * Importa os dados arquivo no banco de dados
     * 
     * @return void
     */
    private function _import(array $arrData, string $mapping, string $nameBank, string $actionName) : void
    {   

        $mapping = json_decode($mapping, true);
        
        try {

            # Indica que a importação iniciou
            DB::statement("UPDATE importations SET status = ? WHERE id = ?", ['P', $this->importationId]);

            DB::beginTransaction();

            # Varre linha por linha
            foreach ($arrData as $data) {
                
                $this->warn("Inserindo novo cliente...");

                # Dados básicos do cliente
                $clientId = $this->_importBasicData($mapping, $data);

                # Dados bancários do cliente
                $this->_importBankData($mapping, $data, $clientId);
                
                # Dados de contato do cliente
                $this->_importContactsData($mapping, $data, $clientId);

                # Imports especificos para cada banco
                switch ($nameBank) {

                    case 'BMG' :
                        $this->_importBmg($mapping, $data, $clientId, $actionName);
                    break;

                }

                # Atualiza a quantidade de importados
                DB::statement("UPDATE importations SET total_success = total_success + 1 WHERE id = ?", [$this->importationId]);

            }
            
            # Cria a higienização
            $res = DB::insert("INSERT INTO sanitations (importation_id, created_at) VALUES (?, ?)", [$this->importationId, date('Y-m-d H:i:s')]);
            if (!$res)
                throw new \Exception("Não foi possivel criar a higienização dos clientes!");

            $this->info("Importação concluida!");
            DB::commit();

            # Indica sucesso na importação
            DB::statement("UPDATE importations SET status = ? WHERE id = ?", ['S', $this->importationId]);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            DB::rollback();

            # Indica erro na importação
            DB::statement("UPDATE importations SET status = ? WHERE id = ?", ['E', $this->importationId]);
        }

    }

    /**
     * Importa os dados básicos do cliente
     * 
     * @return int
     */
    private function _importBasicData(array $mapping, array $data) : int
    {
        $cpf  = null;
        $name = null;

        # CPF
        if (!empty($mapping['cpf']))
            $cpf  = filter_var($data[$mapping['cpf']], FILTER_SANITIZE_STRING);

        # Nome
        if (!empty($mapping['name']))
            $name = filter_var($data[$mapping['name']], FILTER_SANITIZE_STRING);
        
        # Verifica se o cliente já foi inserido e pega o id dele
        $res = DB::select("SELECT id FROM importation_{$this->importationId}_clients WHERE cpf = ?", [$cpf]);
        if (empty($res)) {

            # Insere o client no banco
            $res = DB::insert("INSERT INTO importation_{$this->importationId}_clients (cpf, nome) VALUES (?, ?)", [
                $cpf,
                $name
            ]);
            if (!$res)
                throw new \Exception("Não foi possivel inserir o cliente!");

            $this->info("Dados básicos do cliente inseridos!");

            # Captura o ultimo id inserido
            $lastInsertedId = DB::select('SELECT LAST_INSERT_ID() AS lastInsertedId')[0]->lastInsertedId; 

        } else {
            $lastInsertedId = $res[0]->id;
            $this->warn("Cliente já existe na base, capturando id!");
        }

        return $lastInsertedId;

    }

    /**
     * Importa os dados bancários do cliente
     * 
     * @return void
     */
    private function _importBankData(array $mapping, array $data, int $clientId) : void
    {

        $agency       = null;
        $agencyDigit  = null;
        $bank         = null;
        $account      = null;
        $accountDigit = null;

        # Agencia
        if (!empty($mapping['agency']))
            $agency = filter_var($data[$mapping['agency']], FILTER_SANITIZE_STRING);

        # Digito da agencia
        if (!empty($mapping['agency_digit']))
            $agencyDigit  = filter_var($data[$mapping['agency_digit']], FILTER_SANITIZE_STRING);

        # Banco
        if (!empty($mapping['bank']))
            $bank = filter_var($data[$mapping['bank']],FILTER_SANITIZE_STRING);

        # Conta
        if (!empty($mapping['account']))
            $account = filter_var($data[$mapping['account']],FILTER_SANITIZE_STRING);

        # Digito da conta
        if (!empty($mapping['account_digit']))
            $accountDigit = filter_var($data[$mapping['account_digit']],FILTER_SANITIZE_STRING);
        
        # Insere os dados no banco
        $res = DB::insert("INSERT IGNORE INTO 
                        importation_{$this->importationId}_client_banks (client_id, agency, agency_digit, bank, account, account_digit) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?)", [
            $clientId,
            $agency,
            $agencyDigit,
            $bank,
            $account,
            $accountDigit
        ]);
        if (!$res)
            throw new \Exception("Não foi possivel inserir os dados bancários do cliente!");

        $this->info("Dados bancários do cliente inseridos!");

    }

    /**
     * Importa os dados bancários do cliente
     * 
     * @return void
     */
    private function _importContactsData(array $mapping, array $data, int $clientId) : void
    {

        # Celulares
        if ($mapping['celphone'] != "" && !empty($data[$mapping['celphone']])) {

            $celphones = explode(',', $data[$mapping['celphone']]);
            foreach ($celphones as $celphone) {
                
                if (!empty($celphone)) {

                    # Insere os dados no banco
                    $res = DB::insert("INSERT IGNORE INTO importation_{$this->importationId}_client_contacts (client_id, contact_type_id, contact) VALUES (?, ?, ?)", [
                        $clientId,
                        1,
                        $celphone
                    ]);
                    if (!$res)
                        throw new \Exception("Não foi possivel inserir o contato do cliente!");

                }

            }

            $this->info("Contatos do cliente inseridos!");
            
        } else 
            $this->warn("Cliente sem celulares");

    }

    /**
     * Faz os imports especificos para o bmg
     * 
     * @return void
     */
    private function _importBmg(array $mapping, array $data, int $clientId, string $actionName) : void
    {
        $entityCode           = null;
        $sequentialOrgan      = null;
        $storeCode            = null;
        $serverSituationCode  = null;
        $formCredit           = null;
        $bankCodePaymentOrder = null;
        $shippingFormCode     = null;

        # Código da entidade
        if (!empty($mapping['entity_code']))
            $entityCode = filter_var($data[$mapping['entity_code']], FILTER_SANITIZE_STRING);

        # Orgao sequencial
        if (!empty($mapping['sequential_organ']))
            $sequentialOrgan  = filter_var($data[$mapping['sequential_organ']], FILTER_SANITIZE_STRING);

        # Código da loja
        if (!empty($mapping['store_code']))
            $storeCode = filter_var($data[$mapping['store_code']],FILTER_SANITIZE_STRING);

        # Código de situação do servidor
        if (!empty($mapping['server_situation_code']))
            $serverSituationCode = filter_var($data[$mapping['server_situation_code']],FILTER_SANITIZE_STRING);

        # Forma de crédito
        if (!empty($mapping['form_credit']))
            $formCredit = filter_var($data[$mapping['form_credit']],FILTER_SANITIZE_STRING);

        # Código de banco (Ordem de pagamento)
        if (!empty($mapping['bank_code_payment_order']))
            $bankCodePaymentOrder = filter_var($data[$mapping['bank_code_payment_order']],FILTER_SANITIZE_STRING);

        # Código de forma de envio
        if (!empty($mapping['shipping_form_code']))
            $shippingFormCode = filter_var($data[$mapping['shipping_form_code']],FILTER_SANITIZE_STRING);

        # Insere os dados no banco
        $res = DB::insert("INSERT IGNORE INTO 
                        importation_{$this->importationId}_client_bmgs (client_id, entity_code, sequential_organ, store_code, server_situation_code, form_credit, bank_code_payment_order, shipping_form_code) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?)", [
            $clientId,
            $entityCode,
            $sequentialOrgan,
            $storeCode,
            $serverSituationCode,
            $formCredit,
            $bankCodePaymentOrder,
            $shippingFormCode
        ]);
        if (!$res)
            throw new \Exception("Não foi possivel inserir os dados do BMG do cliente!");

        $this->info("Dados do BMG do cliente inseridos!");
        $this->warn("Inicia importação de dados especificos para '$actionName'!");

        # Inicia as importações especificas do BMG
        if ($actionName == 'Cadastro (Saque)')
            $this->_importBmgWithdraw($mapping, $data, $clientId);

    }

    /**
     * Importa os dados do BMG (Saque)
     * 
     * @return void
     */
    private function _importBmgWithdraw(array $mapping, array $data, int $clientId) : void
    {

        $type           = null;
        $finalityCredit = null;

        # Tipo do saque
        if (!empty($mapping['type']))
            $type  = filter_var($data[$mapping['type']], FILTER_SANITIZE_STRING);

        # Finalidade do crédito
        if (!empty($mapping['finality_credit']))
            $finalityCredit = filter_var($data[$mapping['finality_credit']],FILTER_SANITIZE_STRING);

        # Insere os dados no banco
        $res = DB::insert("INSERT IGNORE INTO 
                        importation_{$this->importationId}_client_bmg_withdraws (client_id, type, finality_credit) 
                    VALUES 
                        (?, ?, ?)", [
            $clientId,
            $type,
            $finalityCredit
        ]);
        if (!$res)
            throw new \Exception("Não foi possivel inserir os dados do BMG do cliente!");

        $this->info("Dados do BMG Cartão inseridos com sucesso!");

    }

}