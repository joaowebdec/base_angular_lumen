<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogSms extends Model
{

    protected $table = 'log_sms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importation_id', 'client_id', 'msg', 'sms_id', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Ativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = true;
}
