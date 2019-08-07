<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeContacts extends Model
{

    use SoftDeletes;

    protected $table = 'type_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];

    /**
     * Ativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = true;
}
