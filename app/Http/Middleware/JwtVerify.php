<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JwtService;

class JwtVerify
{

    private $ignoreRoutes = [
        'users/login'
    ];

    /**
     * Verifica se o token foi enviado e se ele é valido
     */
    public function handle(Request $request, Closure $next)
    {   

        # Verifica se essa rota deve ser ignorada
        if ($this->ignoreRoute($request))
            return $next($request);

        $token = $request->header('Authorization');
        
        # Verifica se o token foi enviado
        if (empty($token))
            return response()->json(['msg' => 'Token não enviado!', 'code' => 401]);
        
        # Verifica se o token é válido
        try {

            $token = JwtService::decode($token);
            $request->merge(['tokenData' => $token]);
            return $next($request);

        } catch (\Exception $e) {
            return response()->json(['msg' => 'Token inválido!', 'code' => 406]);
        }

    }

    /** 
     * Verifica as rotas que deve ignorar 
     * */
    private function ignoreRoute(Request $request) : bool
    {

        $ignore = false;
        foreach ($this->ignoreRoutes as $endpoint) {
            
            if ($request->is($endpoint)) {
                $ignore = true;
                break;
            }

        }
        
        return $ignore;

    }
    
}