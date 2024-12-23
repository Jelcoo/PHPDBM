<?php

namespace App\Repositories;

use App\Exceptions\InvalidDatabaseException;
use App\Helpers\SchemaBuilder;

/**
 * @method self useDatabase(string $database)
 * @method self useTable(string $table)
 */
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

    public function getDatabaseSize(): int
    {
        $statement = $this->getConnection()->prepare("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema=:databaseName GROUP BY table_schema;");
        $statement->execute(['databaseName' => $this->database]);

        return (int) $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getTableSize(): int
    {
        $statement = $this->getConnection()->prepare("SELECT data_length + index_length AS size FROM information_schema.tables WHERE table_schema=:databaseName AND table_name=:tableName;");    
        $statement->execute(['databaseName' => $this->database, 'tableName' => $this->table]);

        return (int) $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getAllTablesFromDatabase(): array
    {
        return $this->getConnection()->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function countDatabaseTables(): int
    {
        return count($this->getAllTablesFromDatabase());
    }

    public function createDatabase(string $databaseName): void
    {
        if (!$this->isValidDatabaseName($databaseName)) {
            throw new InvalidDatabaseException();
        }
        $this->getConnection()->query('CREATE DATABASE ' . $databaseName);
    }

    public function createDatabaseTable(string $tableName, $columns): void
    {
        $schemaBuilder = new SchemaBuilder($this->getConnection());
        $schemaBuilder->table($tableName)->create();

        foreach ($columns as $column) {
            $options = [];
            if ($column['default'] !== null) {
                $options['default'] = $column['default'];
            }
            if ($column['isNull'] === false) {
                $options['nullable'] = false;
            }
            if ($column['isAi'] === true) {
                $options['auto_increment'] = true;
            }
            $schemaBuilder->addColumn($column['name'], 'VARCHAR(255)', $options);
        }
        $schemaBuilder->execute();
    }
}
