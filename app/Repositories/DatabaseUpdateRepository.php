<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;

class DatabaseUpdateRepository extends DatabaseRepository
{
    public function __construct()
    {
        $this->prepareFromSession();
    }

    public function createRow(string $database, string $table, array $data): int
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return 0;
        }
        
        $queryBuilder = new QueryBuilder($this->getConnection());
        return $queryBuilder->table($table)
            ->insert($data);
    }

    public function updateRow(string $database, string $table, string $primaryKey, string $keyValue, array $data): void
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return;
        }

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table)
            ->where($primaryKey, '=', $keyValue)
            ->update($data);
    }
}
