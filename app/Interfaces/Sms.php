<?php

namespace App\Interfaces;

interface Sms
{

    public function send(string $msg, string $number);

}