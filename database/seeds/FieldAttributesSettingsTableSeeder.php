<?php

use Illuminate\Database\Seeder;
use App\FieldAttributesSettings;

class FieldAttributesSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->_paramsSendSms();
        $this->_modelSendSmsBmgSaque();
        $this->_paramsApiBmg();
        
    }

    /**
     * Parametros de envio de SMS
     * 
     * @return void
     */
    private function _paramsSendSms() : void
    {
        FieldAttributesSettings::create([
            'attribute_id' => 1,
            'field_id'     => 1,
            'value'        => 'required'
        ]);

        FieldAttributesSettings::create([
            'attribute_id' => 1,
            'field_id'     => 2,
            'value'        => 'required'
        ]);
    }

    /**
     * Modelo de mensagem para envio de SMS do BMG (Saque)
     * 
     * @return void
     */
    private function _modelSendSmsBmgSaque() : void
    {
        FieldAttributesSettings::create([
            'attribute_id' => 1,
            'field_id'     => 3,
            'value'        => 'required'
        ]);

        FieldAttributesSettings::create([
            'attribute_id' => 4,
            'field_id'     => 3,
            'value'        => '100'
        ]);
    }

    /**
     * Parametros de acesso a api do BMG
     * 
     * @return void
     */
    private function _paramsApiBmg() : void
    {
        FieldAttributesSettings::create([
            'attribute_id' => 1,
            'field_id'     => 4,
            'value'        => 'required'
        ]);

        FieldAttributesSettings::create([
            'attribute_id' => 1,
            'field_id'     => 5,
            'value'        => 'required'
        ]);
    }
}
