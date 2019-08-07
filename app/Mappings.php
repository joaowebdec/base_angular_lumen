<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mappings extends Model
{

    protected $table = 'mappings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'columns'
    ];

    /**
     * Ativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = true;
}
