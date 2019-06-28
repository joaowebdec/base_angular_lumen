<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use App\Http\Validators\AppValidator;

class LoginValidator extends AppValidator
{

    public function __construct(array $params)
    {
        $this->rules = [
            'email'    => 'required',
            'password' => 'required'
        ];

        $this->params = $params;
    }

    
}
