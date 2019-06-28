<?php

namespace App\Http\Validators;

use Validator;

abstract class AppValidator
{

    /**
     * Regras para a validação
     */
    protected $rules;

    /**
     * Mensagens de erro das validações
     */
    protected $messages = [
        'required' => 'O campo :attribute é obrigatório.'
    ];

    /**
     * Dados da validação
     */
    protected $params;

    public function validate()
    {
        $data = Validator::make($this->params, $this->rules, $this->messages);
        return $data->errors()->all();
    }

}