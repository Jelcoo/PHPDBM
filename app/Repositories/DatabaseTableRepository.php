<?php

namespace App\Repositories;

class DatabaseTableRepository extends DatabaseRepository
{
    public function __construct()
    {
        $this->prepareFromSession();
    }

    public function getTableColumns(string $database, string $table): array
    {
        /*
         * I can't bind a parameter in a FROM statement, so I have to check if the database & table name is valid.
         * This ensures that the database or table name is not malicious.
         */
        if (! $this->isValidDatabaseName($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $this->getConnection()->exec("USE `$database`");
        $statement = $this->getConnection()->prepare("SHOW COLUMNS FROM `$table`");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllRowsForTable(string $database, string $table): array
    {
        /*
         * I can't bind a parameter in a FROM statement, so I have to check if the database & table name is valid.
         * This ensures that the database or table name is not malicious.
         */
        if (! $this->isValidDatabaseName($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $this->getConnection()->exec("USE `$database`");
        $statement = $this->getConnection()->prepare("SELECT * FROM `$table`");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
