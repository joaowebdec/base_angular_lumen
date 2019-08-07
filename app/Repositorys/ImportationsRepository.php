<?php

namespace App\Repositorys;

use App\Repositorys\Repository;
use Illuminate\Support\Facades\DB;

class ImportationsRepository extends Repository
{

    public function __construct()
    {
        $this->model = "App\Importations";
    }

    /**
     * Lista as importações
     * 
     * @return Array
     */
    public function findAll(?array $filter = null) : Array
    {   
        if (isset($filter['join']) && $filter['join']) {
            return DB::table("importations AS I")
                    ->select(
                        'I.id',
                        'description',
                        'U.name AS user_name',
                        'B.name AS bank_name',
                        'A.name AS action_name',
                        'total_success',
                        'total_fail',
                        'I.status',
                        'I.created_at')
                    ->join("users AS U", "U.id", "=", "I.user_id")
                    ->join("banks AS B", "B.id", "=", "I.bank_id")
                    ->join("actions AS A", "A.id", "=", "I.action_id")
                    ->get()
                    ->toArray();
        } else
            return parent::findAll($filter);
    }

    public function filter(?array $filters = null) : array
    {

    }

    /**
     * Cria as tabelas de clientes da importação
     * 
     * @return Bool
     */
    public function createTableClients(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_clients` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `cpf` VARCHAR(11) NOT NULL,
            `nome` VARCHAR(60) NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`cpf`)
        )");
    }

    /**
     * Cria as tabelas de dados bancarios do cliente da importação
     * 
     * @return Bool
     */
    public function createTableClientBanks(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_client_banks` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `agency` INT(8) NULL,
            `agency_digit` INT(2) NULL,
            `bank` INT(5) NULL,
            `account` INT(10) NULL,
            `account_digit` INT(2) NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_bank` (`client_id`, `agency`, `bank`, `account`),
            INDEX `fk_importation_{$importationId}_client_banks_client_id_idx` (`client_id` ASC),
            CONSTRAINT `fk_importation_{$importationId}_client_banks_client_id`
              FOREIGN KEY (`client_id`)
              REFERENCES `importation_{$importationId}_clients` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION
        );");
    }

    /**
     * Cria a tabela de matriculas do cliente da importação
     * 
     * @return bool
     */
    public function createTableClientRegistrations(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_client_registrations` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `registration` VARCHAR(30) NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_registration` (`client_id`, `registration`),
            INDEX `fk_importation_{$importationId}_client_registrations_client_id_idx` (`client_id` ASC),
            CONSTRAINT `fk_importation_{$importationId}_client_registrations_client_id`
              FOREIGN KEY (`client_id`)
              REFERENCES `importation_{$importationId}_clients` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION
        );");
    }

    /**
     * Cria a tabela de dados especificos do banco BMG para a importação
     * 
     * @return bool
     */
    public function createTableClientBmgs(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_client_bmgs` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `entity_code` INT(8) NULL,
            `sequential_organ` INT(8) NULL,
            `store_code` INT(11) NULL,
            `server_situation_code` INT(11) NULL,
            `form_credit` INT(2) NULL,
            `bank_code_payment_order` INT(6) NULL,
            `shipping_form_code` INT(2) NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`client_id`),
            INDEX `fk_importation_{$importationId}_client_bmgs_client_id_idx` (`client_id` ASC),
            CONSTRAINT `fk_importation_{$importationId}_client_bmgs_client_id`
              FOREIGN KEY (`client_id`)
              REFERENCES `importation_{$importationId}_clients` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION
        );");
    }

    /**
     * Cria a tabela de contatos do cliente da importação
     * 
     * @return bool
     */
    public function createTableClientContacts(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_client_contacts` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `contact_type_id` INT(10) UNSIGNED NOT NULL,
            `contact` VARCHAR(45) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_contact` (`client_id`, `contact`),
            INDEX `fk_importation_{$importationId}_client_contacts_client_id_idx` (`client_id` ASC),
            INDEX `fk_importation_{$importationId}_client_contacts_type_contact_id_idx` (`contact_type_id` ASC),
            CONSTRAINT `fk_importation_{$importationId}_client_contacts_client_id`
              FOREIGN KEY (`client_id`)
              REFERENCES `importation_{$importationId}_clients` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION,
            CONSTRAINT `fk_importation_{$importationId}_client_contacts_type_contact_id`
              FOREIGN KEY (`contact_type_id`)
              REFERENCES `type_contacts` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION
        );");
    }

    /**
     * Cria a tabela especifica de saque do bmg para o cliente da importação
     * 
     * @return bool
     */
    public function createTableClientBmgWithdraw(int $importationId) : bool
    {
        return DB::statement("CREATE TABLE `importation_{$importationId}_client_bmg_withdraws` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `client_id` INT(11) NOT NULL,
            `value` FLOAT(10, 2) NULL,
            `type` INT(2) NOT NULL,
            `finality_credit` INT(2) NOT NULL,
            `internal_account_number` VARCHAR(30) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY (`client_id`),
            INDEX `fk_importation_{$importationId}_client_bmg_withdraws_client_id_idx` (`client_id` ASC),
            CONSTRAINT `fk_importation_{$importationId}_client_bmg_withdraws_client_id`
              FOREIGN KEY (`client_id`)
              REFERENCES `importation_{$importationId}_clients` (`id`)
              ON DELETE NO ACTION
              ON UPDATE NO ACTION
        );");
    }

}
