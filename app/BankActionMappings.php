<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankActionMappings extends Model
{

    protected $table = 'bank_action_mappings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_id', 'action_id', 'mapping_id'
    ];

    /**
     * Desativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = false;
}
