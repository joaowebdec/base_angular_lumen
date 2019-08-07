<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValuesSettings extends Model
{

    protected $table = 'values_settings';

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
