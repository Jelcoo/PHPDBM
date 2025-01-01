<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;

/**
 * @method self useDatabase(string $database)
 * @method self useTable(string $table)
 */
class DatabaseUpdateRepository extends DatabaseRepository
{
    public function __construct()
    {
        $this->prepareFromSession();
    }

    public function createRow(array $data): int
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        return $queryBuilder->table($this->table)
            ->insert($data);
    }

    public function updateRow(string $primaryKey, string $keyValue, array $data): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table)
            ->where($primaryKey, '=', $keyValue)
            ->update($data);
    }

    public function deleteRow(string $primaryKey, string $keyValue): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table)
            ->where($primaryKey, '=', $keyValue)
            ->delete();
    }
}
