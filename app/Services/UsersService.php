<?php

namespace App\Services;

use App\Users;
use App\Repositorys\UsersRepository;
use Illuminate\Http\Request;
use App\Services\{FileService, FileLocalService};

class UsersService
{

    /**
     * Verifica se o usuário esta ativo
     */
    public static function isEnable(int $userId) : bool
    {

        $user = Users::find($userId);
        if (!$user)
            return false;

        return empty($user->deleted_at);

    }
    
    /**
     * Salva ou altera um usuário
     * 
     * @return Boolean
     */
    public static function save(array $params, ?\Illuminate\Http\UploadedFile $uploadFile = null) : array
    {
        
        $arrRetorno     = ['code' => 200, 'msg' => 'Usuário salvo com sucesso'];
        $userRepository = new UsersRepository();

        if (!isset($params['image']))
            $params['image'] = null;

        try {
            
            # Realiza o upload            
            if ($uploadFile) {
                $image = FileService::upload(new FileLocalService(), $uploadFile, 'users');
                if (!$image)
                    throw new \Exception("Não foi possivel realizar o upload da imagem!", 417);
            } else
                $image = is_string($params['image']) ? $params['image'] : null;

            $params['image'] = $image;

            # Salva o usuário
            if (isset($params['id']) && !empty($params['id'])) {

                unset($params['password']);
                unset($params['_method']);

                # Remove a image anterior
                $user = Users::withTrashed()->find($params['id']);
                if (empty($image) || trim($image) != $user->image)
                    UsersService::removeImage($params['id']);

                $res = $userRepository->update($params['id'], $params);
                
            } else
                $res = $userRepository->insert($params);

            if (!$res)
                throw new \Exception("Não foi possivel salvar o usuário", 417);

        } catch (\Exception $e) {
            $arrRetorno['code'] = $e->getCode();
            $arrRetorno['msg']  = $e->getMessage();
        }
        
        return $arrRetorno;
        
    }

    /**
     * Remove a imagem do usuário
     * 
     * @return Boolean
     */
    public static function removeImage(int $id) : bool
    {
        
        $user = Users::withTrashed()->find($id);
        if (!empty($user->image))
            return FileService::remove(new FileLocalService(), 'users/' . $user->image);
        else
            return true;
    }

    /**
     * Verifica se o email já existe
     * 
     * @return Boolean
     */
    public static function emailExists(string $email) : bool
    {
        $userRepository = new UsersRepository();
        $qtd            = $userRepository->count(['email' => $email]);
        return !empty($qtd);
    }

}
