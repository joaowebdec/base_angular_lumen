<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use Illuminate\Http\UploadedFile;

use App\Services\{FileService, FileLocalService};

class FileTest extends TestCase
{
    /**
     * Teste de insert de usuário
     *
     * @return void
     */
    public function testUploadLocal()
    {
        $image = FileService::upload(new FileLocalService(), UploadedFile::fake()->image('image.jpg'), 'users');
        $this->assertTrue(is_string($image));
    }

    /**
     * Teste de insert de usuário
     * 
     * USE QUANDO NECESSÁRIO, CASO CONTRARIO O TESTE OCASIONARA ERRO
     * 
     * @return void
     */
    // public function testRemoveImageLocal()
    // {
    //     $res = FileService::remove(new FileLocalService(), 'users/7866d188d56bf315cdb92984a6407f04.jpeg');
    //     $this->assertTrue($res);
    // }

}
