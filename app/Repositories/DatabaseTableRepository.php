<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;

/**
 * @method self useDatabase(string $database)
 * @method self useTable(string $table)
 */
class DatabaseTableRepository extends DatabaseRepository
{
    public function __construct()
    {
        $this->prepareFromSession();
    }

    public function getTableColumns(): array
    {
        $statement = $this->getConnection()->prepare("SHOW COLUMNS FROM `{$this->table}`");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllRowsForTable(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table);

        return $queryBuilder->get();
    }

    public function getPagedRowsForTable(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table)
            ->limit($perPage, $offset);

        return $queryBuilder->get();
    }

    public function getPagedSearchedRowsForTable(int $page, int $perPage, string $search): array
    {
        $offset = ($page - 1) * $perPage;
        $tableColumns = $this->getTableColumns();

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table)
            ->limit($perPage, $offset);

        foreach ($tableColumns as $column) {
            $queryBuilder->orWhere($column['Field'], 'LIKE', "%$search%");
        }

        return $queryBuilder->get();
    }

    public function countSearchedRowsForTable(string $search): int
    {
        $tableColumns = $this->getTableColumns();

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table);

        foreach ($tableColumns as $column) {
            $queryBuilder->orWhere($column['Field'], 'LIKE', "%$search%");
        }

        return $queryBuilder->count();
    }

    public function countRowsForTable(): int
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table);

        return $queryBuilder->count();
    }

    public function tableExists(): bool
    {
        $statement = $this->getConnection()->prepare('SHOW TABLES');
        $statement->execute();

        return in_array($this->table, $statement->fetchAll(\PDO::FETCH_COLUMN));
    }

    public function getRowByKey(string $primaryKey, string $key): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($this->table)
            ->where($primaryKey, '=', $key);

        return $queryBuilder->first();
    }
}
