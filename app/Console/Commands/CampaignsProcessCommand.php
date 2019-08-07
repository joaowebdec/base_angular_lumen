<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositorys\CampaignsRepository;
use App\Services\SmsService;

class CampaignsProcessCommand extends Command
{
    /**
     * O nome do comando no console.
     *
     * @var string
     */
    protected $signature = "campaigns-process";

    /**
     * A descricão do comando no console.
     *
     * @var string
     */
    protected $description = "Processa a campanha enviando os SMS's";

    /**
     * Executa o comando no console.
     *
     * @return mixed
     */
    public function handle(CampaignsRepository $campaignsRepository)
    {    
        
        # Busca as campanhas que estão aguardando processamento
        $campaigns = $campaignsRepository->findAll([
            'join'       => true,
            'conditions' => 'C.status = :status',
            'bind' => [
                'status' => 'A'
            ]
        ]);
        
        if (!empty($campaigns)) {

            foreach ($campaigns as $campaign) {

                # Indica que a campanha iniciou
                DB::statement("UPDATE campaigns SET status = ? WHERE id = ?", ['I', $campaign->id]);

                $this->warn("Iniciando processamento da campanha de id: {$campaign->id}");
                
                # Busca os lotes higienizados com sucesso
                $lotClients = DB::select("SELECT 
                                            SL.client_id, 
                                            SL.obs,
                                            CC.contact
                                        FROM 
                                            sanitation_lots AS SL 
                                        INNER JOIN 
                                            importation_{$campaign->importation_id}_client_contacts AS CC ON SL.client_id = CC.client_id  
                                        WHERE SL.status = 'S' AND SL.importation_id = {$campaign->importation_id}");

                # Inicia o envio de SMS's para os clients com a proposta
                foreach ($lotClients as $client) {

                    # Envia e registra o log de envio
                    $res = SmsService::send(new \App\Services\SmsSuperNovaTelecomService(), [
                        'importation_id' => $campaign->importation_id,
                        'client_id'      => $client->client_id,
                        'msg'            => $client->obs,
                        'number'         => $client->contact
                    ]);

                    if ($res)
                        $this->info("Registrando log de envio para o numero {$client->contact} com o client de id {$client->client_id} da importação de id {$campaign->importation_id} ");
                    else
                        $this->error("Erro ao registrar o log de envio de SMS ou enviar o SMS para o numero {$client->contact} com o cliente de id $client->client_id} da importação de id {$campaign->importation_id}");

                }

                # Indica que a campanha finalizou
                DB::statement("UPDATE campaigns SET status = ? WHERE id = ?", ['F', $campaign->id]);

            }

        } else
            $this->error("Nenhuma campanha esperando para ser iniciada!");

    }

}