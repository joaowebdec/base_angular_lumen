<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{

    protected $table = 'campaigns';

    /**
     * Ativa os timestamps 
     *
     * @var bool
     */
    public $timestamps = true;
}
