<?php

namespace App\Repositorys;

use App\Repositorys\Repository;
use Illuminate\Support\Facades\DB;

class CampaignsRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\Campaigns";
    }

    /**
     * Lista as campanhas
     * 
     * @return Array
     */
    public function findAll(?array $filter = null) : Array
    {   

        if (isset($filter['join']) && $filter['join']) {

            if (!isset($filter['conditions']) || empty($filter['conditions'])) {
                $filter['conditions']      = '1 = :default';
                $filter['bind']['default'] = 1;
            }

            return DB::table("campaigns AS C")
                    ->select(
                        'C.id',
                        'C.type_campaign_id',
                        'S.importation_id'
                    )
                    ->join("sanitations AS S", "S.id", "=", "C.sanitation_id")
                    ->whereRaw($filter['conditions'], $filter['bind'])
                    ->get()
                    ->toArray();
        } else
            return parent::findAll($filter);

    }

    public function filter(?array $filters = null) : array
    {

    }

}
