<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Importations extends Model
{


    protected $table = 'importations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'file_name', 'user_id', 'bank_id', 'action_id', 'mapping'
    ];

    /**
     * Ativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = true;
}
