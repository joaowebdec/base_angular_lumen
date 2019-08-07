<?php

namespace App\Repositorys;

use App\Users;
use App\Repositorys\Repository;

class UsersRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\Users";
    }

    /**
     * Insere um novo usuário
     * 
     * @return Boolean
     */
    public function insert(array $params, bool $returnInstance = false) : bool
    {   
        $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
        $user               = $this->model::create($params); 
        return $user instanceof Users;
    }

    /**
     * Lista os usuários
     * 
     * @return Array
     */
    public function findAll(?array $filter = null) : Array
    {
        $filter = $this->filter($filter);
        return parent::findAll($filter);
    }

    /**
     * Faz os filtros do usuário
     * 
     * @return Array
     */
    public function filter(?array $filters = null) : array
    {
        $arrFilters = [
            'conditions' => 'visible = :visible',
            'bind' => [
                'visible' => 1
            ]
        ];

        # Filtros de nome
        if (isset($filters['name']) && !empty($filters['name'])) {
            $arrFilters['conditions']  .= ' AND name like :name';
            $arrFilters['bind']['name'] = $filters['name'] . "%";
        }

        # Filtro de email
        if (isset($filters['email']) && !empty($filters['email'])) {
            $arrFilters['conditions']   .= ' AND email like :email';
            $arrFilters['bind']['email'] = $filters['email'] . "%";
        }

        # Filtro de status
        if (isset($filters['status']) && $filters['status'] != "")
            $arrFilters['conditions'] .= $filters['status'] == '0' ? ' AND deleted_at IS NOT NULL' : ' AND deleted_at IS NULL';

        # Filtro de datas (Periodo)
        if (isset($filters['dateInit']) && !empty($filters['dateInit']) &&
            isset($filters['dateFinal']) && !empty($filters['dateFinal'])) {

            $arrFilters['conditions']      .= ' AND created_at BETWEEN :dateInit AND :dateFinal';
            $arrFilters['bind']['dateInit']  = $filters['dateInit'] . ' 00:00:00';
            $arrFilters['bind']['dateFinal'] = $filters['dateFinal'] . ' 23:59:59';

        } else if ((isset($filters['dateInit']) && !empty($filters['dateInit'])) || # Filtro de data
                   (isset($filters['dateFinal']) && !empty($filters['dateFinal']))) {
            
            $date = isset($filters['dateInit']) && !empty($filters['dateInit']) ? $filters['dateInit'] : $filters['dateFinal'];
                    
            $arrFilters['conditions']  .= ' AND DATE(created_at) = :date';
            $arrFilters['bind']['date'] = $date;

        }

        return $arrFilters;
            
    }

    /**
     * Altera a senha do usuário
     */
    public function changePassword(int $id, string $password) : bool
    {

        if (empty($password))
            return false;

        $user = Users::withTrashed()->where('id', $id);
        return $user->update(['password' => password_hash($password, PASSWORD_BCRYPT)]);

    }

}
