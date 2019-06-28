<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Services\{ UsersService, AuthService };

class AuthTest extends TestCase
{
    /**
     * Teste de sucesso de autenticação
     *
     * @return void
     */
    public function testAuthSuccess()
    {

        $res = AuthService::auth('webdec@webdec.com.br', 'abc@123wd');

        if (!$res)
            $this->assertTrue(false);

        $this->assertTrue($res);

    }

    /**
     * Teste de falha de autenticação
     *
     * @return void
     */
    public function testAuthFail()
    {

        $res = AuthService::auth('admin@admin.com', 'abc@123');
        $this->assertTrue(!$res);

    }


    /**
     * Teste se o usuário esta ativo
     *
     * @return void
     */
    public function testUserEnable()
    {

        $res = UsersService::isEnable(2);
        $this->assertTrue($res);

    }

    /**
     * Teste se o usuário esta inativo
     *
     * @return void
     */
    public function testUserNotEnable()
    {

        $res = UsersService::isEnable(1);
        $this->assertTrue(!$res);

    }

    /**
     * Teste de sucesso de login
     * 
     * @return void
     */
    public function testLoginSuccess()
    {
        $res = AuthService::login('webdec@webdec.com.br', 'abc@123wd');
        $this->assertEquals(200, $res['code']);
    }

    /**
     * Teste de falha de login por não encontrar o email
     * 
     * @return void
     */
    public function testLoginUserNotFound()
    {
        $res = AuthService::login('naoexiste.com.br', 'abc@123');
        $this->assertEquals(404, $res['code']);
    }

    /**
     * Teste de falha de login por usuário não estar ativo
     * 
     * @return void
     */
    public function testLoginUserNotEnable()
    {
        $res = AuthService::login('admin@admin.com.br', 'abc@123');
        $this->assertEquals(403, $res['code']);
    }

    /**
     * Teste de falha de login
     * 
     * @return void
     */
    public function testLoginFail()
    {
        $res = AuthService::login('webdec@webdec.com.br', 'abc@123wds');
        $this->assertEquals(406, $res['code']);
    }


}
