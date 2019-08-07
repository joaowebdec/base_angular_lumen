<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{

    protected $table = 'settings';

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
