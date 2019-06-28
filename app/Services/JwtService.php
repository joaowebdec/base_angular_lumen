<?php

namespace App\Services;

use \Firebase\JWT\JWT;

class JWTService
{

    private static $key = 'c0MuN1C4L3G@lw3Bd3C';

    /**
     * Codifica um array em jwt
     */
    public static function encode(array $params) : string
    {
        return JWT::encode($params, self::$key);
    }

    /**
     * Decodifica um token em um array
     */
    public static function decode(string $token) : array
    {
        return (array) JWT::decode($token, self::$key, ['HS256']);
    }

}
