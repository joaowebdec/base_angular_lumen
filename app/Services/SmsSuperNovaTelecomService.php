<?php

namespace App\Services;

use App\Interfaces\Sms;
use App\Repositorys\SettingsRepository;

class SmsSuperNovaTelecomService implements Sms
{

    /**
     * Enia uma mensagem de sms
     * 
     * @return Bool
     */
    public function send(string $msg, string $number)
    {

        $data = SettingsRepository::get('envio_sms');
        
        # Parametros de envio
        $user = $data['sms_user'];
        $key  = $data['sms_password'];
        $msg  = urlencode($msg . ' Envie (S) para SIM e (N) para NAO.');

        # Curl de envio
        $url  = "http://apisms.supernovatelecom.com.br/bot/send-sms.php?usuario=$user&chave=$key&celular=$number&mensagem=$msg";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $res = json_decode(curl_exec($curl), true);

        return isset($res['id']) && !empty($res['id']) ? $res['id'] : false;

    }

}
