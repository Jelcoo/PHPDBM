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
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $statement = $this->getConnection()->prepare("SHOW COLUMNS FROM `$table`");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllRowsForTable(string $database, string $table): array
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $statement = $this->getConnection()->prepare("SELECT * FROM `$table`");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function tableExists(string $database, string $table): bool
    {
        if (! $this->useDatabase($database)) {
            return false;
        }
        $statement = $this->getConnection()->prepare('SHOW TABLES');
        $statement->execute();

        return in_array($table, $statement->fetchAll(\PDO::FETCH_COLUMN));
    }
}
