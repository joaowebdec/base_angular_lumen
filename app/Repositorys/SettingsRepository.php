<?php

namespace App\Repositorys;

use App\Repositorys\Repository;
use Illuminate\Support\Facades\DB;

class SettingsRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\Settings";
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

    /**
     * Retorna os dados da configuração
     * 
     * @return Array
     */
    public static function get(string $setting) : array
    {

        if (empty($setting))
            return [];

        $data = DB::table("settings AS S")
                    ->select(
                        'FS.name',
                        'value'
                    )
                    ->join("field_settings AS FS", "FS.setting_id", "=", "S.id")
                    ->join("values_settings AS VS", "VS.field_id", "=", "FS.id")
                    ->where('S.name', $setting)
                    ->get()
                    ->toArray();
        
        $arrReturn = [];
        foreach ($data as $d) {
            $arrReturn[$d->name] = $d->value;
        }

        return $arrReturn;

    }

}
