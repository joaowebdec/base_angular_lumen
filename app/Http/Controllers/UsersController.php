<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\{AuthService, UsersService };
use App\Http\Validators\{LoginValidator, UserSaveValidator};
use App\Repositorys\UsersRepository;

class UsersController extends Controller
{

    /** 
     * Lista os usuários
     */
    public function index()
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->findAll($_GET);
        return response()->json(['code' => is_array($res) ? 200 : 500, 'users' => $res]);
    }

    /**
     * Retorna um usuário
     */
    public function getUser(int $id)
    {
        $userRepository = new UsersRepository();
        $res = $userRepository->getById($id);
        return response()->json(['code' => is_array($res) ? 200 : 500, 'user' => $res]);
    }

    /**
     * Realiza o login do usuário
     */
    public function login(Request $request)
    {

        $params = $request->all();
        
        # Valida os dados
        $loginValidator = new LoginValidator($params);
        $errors         = $loginValidator->validate();

        if (!empty($errors))
            return response()->json(['msg' => $errors, 'code' => 400]);

        # Tenta autenticar
        $res  = AuthService::login($params['email'], $params['password']);
        return response()->json($res);

    }

    /**
     * Altera um usuário
     */
    public function update(int $id, Request $request) 
    {

        $params       = $request->all();
        $params['id'] = $id;
        $res          = $this->save($params, $request);

        return response()->json($res);
    }

    /**
     * Insere um usuário
     */
    public function insert(Request $request) 
    {
        $params = $request->all();
        $res    = $this->save($params, $request);

        return response()->json($res);
    }

    /**
     * Insere ou altera um usuário
     */
    private function save(array $params, Request $request) : array
    {
        
        $userValidator = new UserSaveValidator($params, $request->getMethod());
        $errors        = $userValidator->validate();

        if (!empty($errors))
            return ['msg' => $errors, 'code' => 400];
        
        # Tenta realizar os saves
        if ($request->hasFile('image'))
            $res = UsersService::save($params, $request->file('image'));
        else
            $res = UsersService::save($params, null);
        
        return $res;

    }

    /**
     * Realiza o soft delete do usuário
     */
    public function delete(int $id)
    {   
        $userRepository = new UsersRepository();
        $res = $userRepository->delete($id);
        return response()->json([
            'code' => $res ? 200 : 500, 
            'msg' => $res ? 'Usuário inativado com sucesso!' : 'Não foi possivel inativar o usuário, verifique se o mesmo já se encontrada inativo.'
        ]);
    }

    /**
     * Ativa um usuário "deletado" via soft delete
     */
    public function restore(int $id)
    {   
        $userRepository = new UsersRepository();
        $res = $userRepository->restore($id);
        return response()->json([
            'code' => $res ? 200 : 500, 
            'msg' => $res ? 'Usuário ativado com sucesso!' : 'Não foi possivel ativar o usuário.'
        ]);
    }

    /**
     * Verifica se o email já existe
     */
    public function email(string $email)
    {
        $res = UsersService::emailExists(base64_decode($email));
        return response()->json([
            'code' => $res ? 406 : 200, 
            'msg'  => $res ? 'Email já cadastrado no sistema!' : 'Email liberado para uso.'
        ]);
    }

    /**
     * Altera a senha de um usuário
     */
    public function password(int $id, Request $request)
    {   
        $password       = $request->input('password');
        $userRepository = new UsersRepository();
        $res            = $userRepository->changePassword($id, $password);

        return response()->json([
            'code' => $res ? 200 : 500, 
            'msg'  => $res ? 'Senha alterada com sucesso!' : 'Não foi possivel alterar a senha do usuário.'
        ]);
    }

}
