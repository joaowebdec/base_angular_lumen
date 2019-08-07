<?php

use Illuminate\Database\Seeder;
use App\Settings;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'id'          => 1,
            'name'        => 'envio_sms',
            'title'       => 'Dados para envio de SMS',
            'description' => 'Configurações de parametros para o uso da API de envio de SMS'
        ]);

        Settings::create([
            'id'          => 2,
            'name'        => 'modelo_envio_sms_saque',
            'title'       => 'Modelo de envio de SMS',
            'description' => "Uma mensagem padrão que será usada para enviar suas mensages SMS's para os clientes"
        ]);

        Settings::create([
            'id'          => 3,
            'name'        => 'acesso_api_bmg',
            'title'       => 'Dados de acesso a API do BMG',
            'description' => "Parametros de envio para acessar a API do BMG"
        ]);
    }
}
