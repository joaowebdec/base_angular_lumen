<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Services\JwtService;
use App\Users;

class JwtTest extends TestCase
{

    /**
     * Teste de sucesso de autenticação
     *
     * @return void
     */
    public function testJwtEncode()
    {   
        
        $jwt = JwtService::encode([
            'id'         => 1,
            'name'       => 'Admin',
            'email'      => 'admin@admin.com.br',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->assertTrue(strlen($jwt) == 197);

    }

    /**
     * Teste de sucesso de autenticação
     *
     * @return void
     */
    public function testJwtDecode()
    {

        $jwt = JwtService::encode([
            'id'         => 1,
            'name'       => 'Admin',
            'email'      => 'admin@admin.com.br',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $arrJwt = JwtService::decode($jwt);
        $this->assertTrue(count($arrJwt) == 4);

    }


}
