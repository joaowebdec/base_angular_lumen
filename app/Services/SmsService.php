<?php

namespace App\Services;

use App\Repositorys\LogSmsRepository;

class SmsService
{

    /**
     * Enia uma mensagem de sms
     */
    public static function send(\App\Interfaces\Sms $sms, array $data) : bool
    {

        $res = $sms->send($data['msg'], $data['number']);
        if (!$res)
            return false;

        # Registra o log de envio de sms
        $logSmsRepository = new LogSmsRepository();
        return $logSmsRepository->insert([
            'importation_id' => $data['importation_id'],
            'client_id'      => $data['client_id'],
            'msg'            => $data['msg'],
            'sms_id'         => $res,
            'status'         => 'ENVIADO'
        ]);

    }

}
