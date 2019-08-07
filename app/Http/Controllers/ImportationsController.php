<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banks;
use App\Http\Validators\ImportationSaveValidator;
use App\Repositorys\{BankActionMappingsRepository, ImportationsRepository};
use App\Services\ImportationService;

class ImportationsController extends Controller
{

    /**
     * Repositorio do bankActionMapping
     */
    private $bankActionMappingsRepository;

    public function __construct(BankActionMappingsRepository $bankActionMappingsRepository) 
    {
        $this->bankActionMappingsRepository = $bankActionMappingsRepository;
    }

    /**
     * Lista os bancos disponiveis para importação
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanks() : \Illuminate\Http\JsonResponse
    {
        $banks = Banks::all('id', 'name')->toArray();
        return response()->json(['code' => is_array($banks) ? 200 : 500, 'banks' => $banks]);
    }
    
    /**
     * Lista as ações disponiveis para o banco
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankActions(int $bankId) : \Illuminate\Http\JsonResponse
    {
        $actions = $this->bankActionMappingsRepository->getBankActions($bankId);
        return response()->json(['code' => is_array($actions) ? 200 : 500, 'actions' => $actions]);
    }

    /**
     * Captura o mapeamento para a ação do banco
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankActionMappings(int $bankId, int $actionId) : \Illuminate\Http\JsonResponse
    {
        $mappings = $this->bankActionMappingsRepository->getBankActionMappings($bankId, $actionId);
        return response()->json(['code' => is_array($mappings) ? 200 : 500, 'mappings' => $mappings]);
    }

    /**
     * Realiza a importação do arquivos
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request) : \Illuminate\Http\JsonResponse
    {
        
        $params               = $request->all();
        $params['user_id']    = $request->all()['tokenData']['id'];
        $importationValidator = new ImportationSaveValidator($params);
        $errors               = $importationValidator->validate();

        # Validação dos campos
        if (!empty($errors))
            return ['msg' => $errors, 'code' => 400];

        # Validação do arquivo
        if (empty($request->file('file')))
            return ['msg' => 'O campo file é obrigatório', 'code' => 400];

        $res = ImportationService::save($params, $request->file('file'));
        return response()->json(['code' => $res['code'], 'msg' => $res['msg']]);
        
    }

    /**
     * Lista as importações 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listImports(ImportationsRepository $importationRepository) : \Illuminate\Http\JsonResponse
    {
        $res = $importationRepository->findAll(['join' => true]);
        return response()->json(['code' => is_array($res) ? 200 : 500, 'imports' => $res]);
    }

}
