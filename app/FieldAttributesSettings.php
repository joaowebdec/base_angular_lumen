<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldAttributesSettings extends Model
{

    protected $table = 'field_attributes_settings';

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
