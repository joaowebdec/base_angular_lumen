<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldSettings extends Model
{

    protected $table = 'field_settings';

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
