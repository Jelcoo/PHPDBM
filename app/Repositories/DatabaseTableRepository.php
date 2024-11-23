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

    public function getPagedRowsForTable(string $database, string $table, int $page, int $perPage): array
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return [];
        }
        $offset = ($page - 1) * $perPage;
        $statement = $this->getConnection()->prepare("SELECT * FROM `$table` LIMIT $perPage OFFSET $offset");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countRowsForTable(string $database, string $table): int
    {
        if (! $this->useDatabase($database) || ! $this->isValidTableName($table)) {
            return 0;
        }
        $statement = $this->getConnection()->prepare("SELECT COUNT(*) FROM `$table`");
        $statement->execute();

        return (int) $statement->fetchColumn();
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
