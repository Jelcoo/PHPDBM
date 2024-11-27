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

    public function getDatabaseSize(string $databaseName): int
    {
        if (! $this->useDatabase($databaseName)) {
            return 0;
        }

        $statement = $this->getConnection()->prepare("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema=:databaseName GROUP BY table_schema;");
        $statement->execute(['databaseName' => $databaseName]);

        return (int) $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getTableSize(string $databaseName, string $tableName): int
    {
        if (! $this->useDatabase($databaseName) || ! $this->isValidTableName($tableName)) {
            return 0;
        }

        $statement = $this->getConnection()->prepare("SELECT data_length + index_length AS size FROM information_schema.tables WHERE table_schema=:databaseName AND table_name=:tableName;");    
        $statement->execute(['databaseName' => $databaseName, 'tableName' => $tableName]);

        return (int) $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getAllTablesFromDatabase(string $databaseName): array
    {
        if (! $this->useDatabase($databaseName)) {
            return [];
        }

        return $this->getConnection()->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function countDatabaseTables(string $databaseName): int
    {
        return count($this->getAllTablesFromDatabase($databaseName));
    }
}
