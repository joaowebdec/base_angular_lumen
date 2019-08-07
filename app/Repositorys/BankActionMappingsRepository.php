<?php

namespace App\Repositorys;

use App\Repositorys\Repository;
use Illuminate\Support\Facades\DB;

class BankActionMappingsRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\BankActionMappings";
    }

    public function findAll(?array $filter = null) : Array
    {
        return parent::findAll($filter);
    }

    public function filter(?array $filters = null) : array
    {

    }

    /**
     * Retorna as ações disponives para o banco
     * 
     * @return array
     */
    public function getBankActions(int $bankId) : array
    {

        return DB::table("bank_action_mappings")
                    ->select(
                        'actions.id', 
                        'actions.name'
                    )
                    ->join("actions", "actions.id", "=", "bank_action_mappings.action_id")
                    ->where('bank_action_mappings.bank_id', '=', $bankId)
                    ->get()
                    ->toArray();

    }

    /**
     * Retorna as ações disponives para o banco
     * 
     * @return array
     */
    public function getBankActionMappings(int $bankId, int $actionId) : array
    {

        $data = DB::table("bank_action_mappings")
                    ->select(
                        'mappings.id', 
                        'mappings.columns'
                    )
                    ->join("mappings", "mappings.id", "=", "bank_action_mappings.mapping_id")
                    ->where('bank_action_mappings.bank_id', '=', $bankId)
                    ->where('bank_action_mappings.action_id', '=', $actionId)
                    ->get()
                    ->toArray();
        
        $data[0]->columns = json_decode($data[0]->columns, true);
        return $data;

    }

}
