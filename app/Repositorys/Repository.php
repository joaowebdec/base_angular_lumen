<?php

namespace App\Repositorys;
use Illuminate\Support\Facades\DB;

abstract class Repository
{

    protected $model;

    /**
     * Insere um novo usuário
     * 
     * @return Boolean
     */
    public function insert(array $params, bool $returnInstance = false)
    {   
        $entity = $this->model::create($params); 

        if ($returnInstance)
            return $entity;

        return $entity instanceof $this->model;
    }

    /**
     * Altera um usuário
     * 
     * @return Boolean
     */
    public function update(int $id, array $params) : bool
    {   

        # Verifica se foi passado o id
        if (empty($id))
            return false;

        $entity = $this->model::withTrashed()->where('id', $id);
        unset($params['tokenData']);
        return $entity->update($params);

    }

    /**
     * Lista os usuários
     * 
     * @return Array
     */
    public function findAll(?array $filter = null) : Array
    {
        $table = strtolower(explode('\\', $this->model)[1]);

        if (empty($filter)) {
            $filter['conditions']      = '1 = :default';
            $filter['bind']['default'] = 1;
        }

        return DB::table($table)->whereRaw($filter['conditions'], $filter['bind'])->get()->toArray();   
    }

    /**
     * Delete um usuário via softDelete
     * 
     * @return Boolean
     */
    public function delete(int $id) : bool
    {
        $entity = $this->model::where('id', $id);
        return $entity->delete();
    }

    /**
     * Delete varias registros de acordo com o where
     * 
     * @return Boolean
     */
    public function deleteWhere(array $filter) : bool
    {
        $table = strtolower(explode('\\', $this->model)[1]);
        return DB::table($table)->whereRaw($filter['conditions'], $filter['bind'])->delete();
    }

    /**
     * Restaura um usuário deletado via softDelete
     * 
     * @return Boolean
     */
    public function restore(int $id) : bool
    {
        $entity = $this->model::onlyTrashed()->where('id', $id);
        return $entity->restore();
    }

    /**
     * Retorna a quantidade de uma consulta
     * 
     * @return Int
     */
    public function count(array $filters, bool $withTrashed = true) : int
    {
        if ($withTrashed)
            return $this->model::withTrashed()->where($filters)->count();
        
        return $this->model::where($filters)->count();
    }

    /**
     * Retorna os dados de um id especifico
     * 
     * @return Int $id
     */
    public function getById(int $id, bool $withTrashed = true) : array
    {
        if ($withTrashed)
            return $this->model::withTrashed()->where('id', $id)->get()->toArray()[0];

        return $this->model::where('id', $id)->get()->toArray()[0];
    }

    /**
     * Assinatura para filtros
     * 
     * @return Array
     */
    public abstract function filter(?array $filters = null) : array;

}
