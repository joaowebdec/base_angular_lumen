<?php

namespace App\Services;

use App\Users;
use App\Services\{UsersService, JwtService};

class AuthService
{

    /**
     * Realiza o login do usuário
     */
    public static function login(string $email, string $password) : array
    {   

        $arrRetorno = [
            'msg'  => 'Usuário autenticado com sucesso!',
            'code' => 200
        ];

        try {

            # Verifica se o email existe
            $user = Users::withTrashed()->where('email', $email)->first();
            if (!$user)
                throw new \Exception("Email não encontrado!", 404);

            # Verifica se esta ativo
            if (!UsersService::isEnable($user->id))
                throw new \Exception("Este usuário esta inátivo! Entre em contato com seu admnistrador!", 403);

            # Tenta se autenticar
            if (!self::auth($email, $password))
                throw new \Exception("Email ou senha inválidos!", 406);

            # Gera token
            $arrRetorno['token'] = JwtService::encode($user->toArray());

        } catch (\Exception $e) {
            $arrRetorno['msg']  = $e->getMessage();
            $arrRetorno['code'] = $e->getCode();
        } finally {
            return $arrRetorno;
        }

    }

    /**
     * Autentica um usuário
     */
    public static function auth(string $email, string $password) : bool
    {

        $user = Users::where('email', $email)->first();

        # Verifica usuário e senha
        if ($user && password_verify($password, $user->password))
            return true;
        else
            return false;

    }

}
