<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributesSettings extends Model
{

    protected $table = 'attributes_settings';

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
