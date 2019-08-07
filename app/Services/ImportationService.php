<?php

namespace App\Services;

use App\Repositorys\{ImportationsRepository, BankActionMappingsRepository};
use App\{Banks, Actions};
use Illuminate\Http\Request;
use App\Services\{FileService, FileLocalService};
use Illuminate\Support\Facades\DB;

class ImportationService
{

    /**
     *  Salva a importação e realiza o upload dos dados 
     * 
     * @return Array
     */
    public static function save(array $params, \Illuminate\Http\UploadedFile $uploadFile) : array
    {   
        $arrRetorno            = ['code' => 200, 'msg' => 'Importação realizada com sucesso! Aguarde o processamento do arquivo.'];
        $importationRepository = new ImportationsRepository();

        DB::beginTransaction();

        try {

            # Tenta fazer o upload do arquivo
            $file = FileService::upload(new FileLocalService(), $uploadFile, 'imports');
            if (!$file)
                throw new \Exception("Não foi possivel realizar o upload do arquivo!", 417);

            # Insere o registro no banco
            $params['file_name'] = $file;
            $importationId       = $importationRepository->insert($params, true)->id;
            if (empty($importationId))
                throw new \Exception("Não foi possivel salvar a importação", 500);

            /*****************************************************************/
            /*                  Inicia a criação de tabelas 
            /*****************************************************************/

            # Cria a tabela de clientes
            $res = $importationRepository->createTableClients($importationId);
            if (!$res)
                throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1000);

            # Cria a tabela de dados bancarios do cliente
            $res = $importationRepository->createTableClientBanks($importationId);
            if (!$res)
                throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1001);

            # Cria a tabela de matriculas do cliente
            $res = $importationRepository->createTableClientRegistrations($importationId);
            if (!$res)
                throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1002);
            
            # Cria a tabela de contatos do cliente
            $res = $importationRepository->createTableClientContacts($importationId);
            if (!$res)
                throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1003);

            # Cria as tabelas especificas de cada banco/ação
            self::_createEspecificTables($params['bank_id'], $params['action_id'], $importationId, $importationRepository);

            DB::commit();

        } catch (\Exception $e) {
            $arrRetorno['code'] = $e->getCode();
            $arrRetorno['msg']  = $e->getMessage();
            DB::rollback();
        }

        return $arrRetorno;

    }

    /**
     * Encaminha para criação de tabelas especificas
     * 
     * @return void
     */
    private static function _createEspecificTables(int $bankId, int $actionId, int $importationId, ImportationsRepository $importationRepository) : void
    {

        $nomeBanco  = Banks::find($bankId)['name'];
        $nomeAction = Actions::find($actionId)['name'];

        switch ($nomeBanco) {

            case 'BMG' : 

                # Cria as tabelas em comum do BMG
                $res = $importationRepository->createTableClientBmgs($importationId);
                if (!$res)
                    throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1005);

                # Cria tabela de saque do BMG
                if (trim($nomeAction) == 'Cadastro (Saque)') {
                    $res = $importationRepository->createTableClientBmgWithdraw($importationId);
                    if (!$res)
                        throw new \Exception("Desculpe, não foi possivel iniciar o processo!", 1006);
                }


            break;

        }

    }

}
