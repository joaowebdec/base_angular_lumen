<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use App\Http\Validators\AppValidator;

class ImportationSaveValidator extends AppValidator
{

    public function __construct(array $params)
    {
        
        $this->rules = [
            'description' => 'required',
            'bank_id'     => 'required',
            'action_id'   => 'required'
        ];

        $this->params = $params;
    }

    
}
