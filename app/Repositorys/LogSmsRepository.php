<?php

namespace App\Repositorys;

use App\Repositorys\Repository;
use Illuminate\Support\Facades\DB;

class LogSmsRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\LogSms";
    }

    /**
     * Lista as campanhas
     * 
     * @return Array
     */
    public function findAll(?array $filter = null) : Array
    {   
        return parent::findAll($filter);
    }

    public function filter(?array $filters = null) : array
    {

    }

}
