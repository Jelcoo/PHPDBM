<?php

namespace App\Repositories;

class DatabaseDiscoveryRepository extends DatabaseRepository
{
    public function __construct()
    {
        $this->prepareFromSession();
    }

    public function getAllDatabases(): array
    {
        return $this->getConnection()->query('SHOW DATABASES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function GetAllTablesFromDatabase(string $databaseName): array
    {
        /*
         * I can't bind a parameter in a SHOW TABLES query, so I have to check if the database name is valid.
         * This ensures that the database name is not malicious.
         */
        if (! $this->isValidDatabaseName($databaseName)) {
            return [];
        }
        return $this->getConnection()->query("SHOW TABLES FROM `$databaseName`")->fetchAll(\PDO::FETCH_COLUMN);
    }
}
