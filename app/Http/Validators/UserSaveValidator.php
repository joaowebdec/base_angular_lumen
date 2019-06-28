<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use App\Http\Validators\AppValidator;

class UserSaveValidator extends AppValidator
{

    public function __construct(array $params, string $method)
    {
        
        $this->rules = [
            'name'  => 'required',
            'email' => 'required',
        ];

        if ($method != 'POST')
            $this->rules['id'] = 'required';
        else
            $this->rules['password'] = 'required';

        $this->params = $params;
    }

    
}
