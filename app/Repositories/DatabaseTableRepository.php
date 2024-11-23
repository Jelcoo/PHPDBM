<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;

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

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table);

        return $queryBuilder->get();
    }

    public function getPagedRowsForTable(string $database, string $table, int $page, int $perPage): array
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $offset = ($page - 1) * $perPage;

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table)
            ->limit($perPage, $offset);

        return $queryBuilder->get();
    }

    public function getPagedSearchedRowsForTable(string $database, string $table, int $page, int $perPage, string $search): array
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $offset = ($page - 1) * $perPage;
        $tableColumns = $this->getTableColumns($database, $table);

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table)
            ->limit($perPage, $offset);

        foreach ($tableColumns as $column) {
            $queryBuilder->orWhere($column['Field'], 'LIKE', "%$search%");
        }

        return $queryBuilder->get();
    }

    public function countSearchedRowsForTable(string $database, string $table, string $search): int
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return 0;
        }
        $tableColumns = $this->getTableColumns($database, $table);

        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table);

        foreach ($tableColumns as $column) {
            $queryBuilder->orWhere($column['Field'], 'LIKE', "%$search%");
        }

        return $queryBuilder->count();
    }

    public function countRowsForTable(string $database, string $table): int
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return 0;
        }
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table($table);

        return $queryBuilder->count();
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
