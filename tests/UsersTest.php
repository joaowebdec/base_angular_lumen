<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use Illuminate\Http\UploadedFile;

use App\Repositorys\UsersRepository;
use App\Services\UsersService;

class UsersTest extends TestCase
{
    /**
     * Teste de insert de usuário
     *
     * @return void
     */
    public function testInsert()
    {

        $userRepository = new UsersRepository();
        $res = $userRepository->insert([
            'name'     => 'Teste Comunica Legal',
            'email'    => 'teste@teste.com.br',
            'password' => 'abc@123teste',
            'image'    => 'teste.jpg'
        ]);
        $this->assertTrue($res);

    }

    /**
     * Teste de update de usuário
     *
     * @return void
     */
    public function testUpdate()
    {

        $userRepository = new UsersRepository();
        $res = $userRepository->update(3, [
            'name'     => 'Teste Comunica (Alterado)',
            'email'    => 'teste_alterado@teste.com.br',
            'image'    => null
        ]);
        $this->assertTrue($res);

    }

    /**
     * Salva o usuário sem imagem
     *
     * @return void
     */
    public function testSaveInsertWithoutImage()
    {

        $res = UsersService::save([
            'name'     => 'Teste Save',
            'email'    => 'teste@save.com.br',
            'password' => 'abc@123save',
            'image'    => null
        ], 
        null);
        $this->assertTrue($res['code'] == 200);

    }

    /**
     * Salva o usuário sem imagem
     *
     * @return void
     */
    public function testSaveInsertImage()
    {

        $res = UsersService::save([
            'name'     => 'Teste Save (Image)',
            'email'    => 'testeimage@save.com.br',
            'password' => 'abc@123save',
            'image'    => UploadedFile::fake()->image('image.jpg')
        ], 
        UploadedFile::fake()->image('image.jpg'));
        $this->assertTrue($res['code'] == 200);

    }

    /**
     * Salva (Altera) o usuário com imagem
     *
     * @return void
     */
    public function testSaveUpdateImage()
    {

        $res = UsersService::save([
            'id'       => 4,
            'name'     => 'Teste Save Update (Image)',
            'email'    => 'testeupdateimage@save.com.br',
            'image'    => UploadedFile::fake()->image('image.jpg')
        ], 
        UploadedFile::fake()->image('image.jpg'));
        $this->assertTrue($res['code'] == 200);

    }

    /**
     * Salva (Altera) o usuário sem imagem
     *
     * @return void
     */
    public function testSaveUpdateWithoutImage()
    {

        $res = UsersService::save([
            'id'       => 5,
            'name'     => 'Teste Save Update (Sem Image)',
            'email'    => 'testeupdatesemimage@save.com.br',
            'image'    => 'nomeimage.jpg'
        ], 
        null);
        $this->assertTrue($res['code'] == 200);

    }

    /**
     * Salva (Altera) o usuário sem imagem
     *
     * @return void
     */
    public function testSaveUpdateRemoveImage()
    {

        $res = UsersService::save([
            'id'       => 5,
            'name'     => 'Teste Save Update (Removendo Image)',
            'email'    => 'testeupdateremoveimage@save.com.br',
            'image'    => null
        ], 
        null);
        $this->assertTrue($res['code'] == 200);

    }

    /**
     * Remove a imagem de um usuário
     * 
     * @return void
     */
    public function testRemoveImage()
    {
        $res = UsersService::removeImage(1);
        $this->assertTrue($res);
    }

    /**
     * Lista os usuários sem filtrar
     * 
     * @return void
     */
    public function testFindUsers()
    {   
        $userRepository = new UsersRepository();
        $res = $userRepository->findAll();
        $this->assertTrue(count($res) >= 2);
    }

    /**
     * Lista os usuários filtrando
     * 
     * @return void
     */
    public function testFindUsersFilter()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->findAll(['name' => 'Admin']);
        $this->assertTrue(count($res) == 1);
    }

    /**
     * Delete um usuário via soft delete
     * 
     * @return void
     */
    public function testDelete()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->delete(2);
        $this->assertTrue($res);
    }

    /**
     * Restaura um usuário deletado via softdelete
     * 
     * @return void
     */
    public function testRestore()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->restore(2);
        $this->assertTrue($res);
    }

    /**
     * Teste a verificação de um email que já existe
     * 
     * @return void
     */
    public function testEmailExists()
    {
        $res = UsersService::emailExists('webdec@webdec.com.br');
        $this->assertTrue($res);
    }


    /**
     * Teste a verificação de um email que já existe/não existe
     * 
     * @return void
     */
    public function testEmailNotExists()
    {
        $res = UsersService::emailExists('crm@webdec.com.br');
        $this->assertTrue(!$res);
    }
    
    /**
     * Retorno o usuário pelo id
     * 
     * @return void
     */
    public function testGetUserById()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->getById(1);
        $this->assertTrue($res['id'] == 1);
    }

    /**
     * Altera a senha do usuário
     * 
     * @return void
     */
    public function testChangePassword()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->changePassword(1, 'abc@123');
        $this->assertTrue($res);
    }
    
}
