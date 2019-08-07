<?php

use Illuminate\Database\Seeder;
use App\FieldSettings;

class FieldSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_fieldsSms();
        $this->_fieldsModeloSmsSaque();
        $this->_fieldsApiBmg();
    }

    /**
     * Cria os campos da configuração de SMS
     * 
     * @return void
     */
    private function _fieldsSms() : void
    {
        FieldSettings::create([
            'id'          => 1,
            'name'        => 'sms_user',
            'label'       => 'Usuário',
            'type'        => 'VARCHAR',
            'setting_id'  => 1
        ]);

        FieldSettings::create([
            'id'          => 2,
            'name'        => 'sms_password',
            'label'       => 'Senha',
            'type'        => 'PASSWORD',
            'setting_id'  => 1
        ]);
    }

      /**
     * Cria os campos da configuração de modelo de envio SMS para saque
     * 
     * @return void
     */
    private function _fieldsModeloSmsSaque() : void
    {
        FieldSettings::create([
            'id'          => 3,
            'name'        => 'modelo_envio_sms_saque',
            'label'       => 'Modelo de mensagem para envio de SMS (Saque)',
            'type'        => 'TEXT',
            'setting_id'  => 2
        ]);
    }

    /**
     * Cria os campos de acesso a API
     * 
     * @return void
     */
    private function _fieldsApiBmg() : void
    {
        FieldSettings::create([
            'id'          => 4,
            'name'        => 'api_bmg_user',
            'label'       => 'Usuário',
            'type'        => 'VARCHAR',
            'setting_id'  => 3
        ]);

        FieldSettings::create([
            'id'          => 5,
            'name'        => 'api_bmg_password',
            'label'       => 'Senha',
            'type'        => 'PASSWORD',
            'setting_id'  => 3
        ]);
    }

}
