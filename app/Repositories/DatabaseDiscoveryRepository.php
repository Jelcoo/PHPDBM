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
        return $this->getConnection()->query('SHOW TABLES FROM ' . $databaseName)->fetchAll(\PDO::FETCH_COLUMN);
    }
}
