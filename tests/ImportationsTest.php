<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Repositorys\{ImportationsRepository, BankActionMappingsRepository};

class ImportationsTest extends TestCase
{
    /**
     * Teste para listar as ações dos bancos
     *
     * @return void
     */
    public function testListBankActions()
    {

        $bankActionMappingsRepository = new BankActionMappingsRepository();
        $res                          = $bankActionMappingsRepository->getBankActions(1);
        $this->assertTrue(!empty($res));

    }

    /**
     * Teste para listar os mapeamentos de um banco/ação
     *
     * @return void
     */
    public function testListBankActionMappings()
    {

        $bankActionMappingsRepository = new BankActionMappingsRepository();
        $res                          = $bankActionMappingsRepository->getBankActionMappings(1, 1);
        $this->assertTrue(!empty($res));

    }

    /**
     * Testa o save da importação
     * 
     * @return void
     */
    public function testSaveImport()
    {

        $importationRepository = new ImportationsRepository();
        $res = $importationRepository->insert([
            'description' => 'Teste',
            'file_name'   => 'teste123.jpg',
            'user_id'     => 1,
            'bank_id'     => 1,
            'action_id'   => 1,
            'mapping'     => json_encode(['cpf' => 11122233345])
        ]);
        $this->assertTrue(!empty($res));

    }

    /**
     * Lista as importações
     * 
     * @return void
     */
    public function testListImports()
    {

        $importationRepository = new ImportationsRepository();
        $res = $importationRepository->findAll(['join' => true]);
        $this->assertTrue(!empty($res));

    }
    

        
}
