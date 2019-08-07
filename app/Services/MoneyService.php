<?php

namespace App\Services;

class MoneyService
{

    /**
     * Coloca o numero no padrão para ser salvo no banco
     * 
     * @return string
     */
    public static function formatToSql(string $value) : string
    {
        $newValue = str_replace('R$', '', $value);
        $newValue = str_replace(',', '.', str_replace('.', '', $newValue));
        return number_format($newValue, 2, '.', '');
    }

}
