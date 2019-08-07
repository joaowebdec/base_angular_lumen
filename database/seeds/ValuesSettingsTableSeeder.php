<?php

use Illuminate\Database\Seeder;
use App\ValuesSettings;

class ValuesSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_valuesSms();
        $this->_valuesModeloSmsEnvioSaque();
        $this->_valuesBmg();
    }

    /**
     * Valores dos campos para envio de SMS
     * 
     * @return void
     */
    private function _valuesSms() : void
    {
        ValuesSettings::create([
            'value'    => 'webcred',
            'field_id' => '1'
        ]);

        ValuesSettings::create([
            'value'    => 'web@cred2019',
            'field_id' => '2'
        ]);
    }

    /**
     * Valores do campos de modelo de envio de SMS para o saque
     * 
     * @return void
     */
    private function _valuesModeloSmsEnvioSaque() : void
    {
        ValuesSettings::create([
            'value'    => 'BMG informa! Saque disponível no valor de R$ {valorSaque}, deseja retirar?',
            'field_id' => '3'
        ]);
    }

    /**
     * Valores dos campos de acesso a API do BGM
     * 
     * @return void
     */
    private function _valuesBmg() : void
    {
        ValuesSettings::create([
            'value'    => 'silvia.mara',
            'field_id' => '4'
        ]);

        ValuesSettings::create([
            'value'    => '836fa2d%',
            'field_id' => '5'
        ]);
    }

}