<?php

namespace App\Repositories;

use App\Helpers\SchemaAlter;
use App\Helpers\SchemaBuilder;
use App\Exceptions\InvalidTableException;
use App\Exceptions\InvalidColumnException;
use App\Exceptions\InvalidDatabaseException;

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
        $statement = $this->getConnection()->prepare('SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema=:databaseName GROUP BY table_schema;');
        $statement->execute(['databaseName' => $this->database]);

        return (int) $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getTableSize(): int
    {
        $statement = $this->getConnection()->prepare('SELECT data_length + index_length AS size FROM information_schema.tables WHERE table_schema=:databaseName AND table_name=:tableName;');
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
            if (!DatabaseRepository::isValidColumnName($column['name'])) {
                throw new InvalidColumnException($column['name']);
            }

            $options = SchemaBuilder::parseColumnOptions($column);
            $options['new'] = true;
            $columnType = SchemaBuilder::parseColumnType($column['type'], $column['length']);
            $schemaBuilder->addColumn($column['name'], $columnType, $options);
        }
        $schemaBuilder->execute();
    }

    public function updateDatabaseTable($columns): void
    {
        $schemaAlter = new SchemaAlter($this->getConnection());
        $schemaAlter->table($this->table);

        foreach ($columns as $column) {
            if ($column['action'] === 'delete') {
                $schemaAlter->dropColumn($column['column']['name']);
            } elseif ($column['action'] === 'update') {
                $runChange = false;
                foreach ($column['updates'] as $update) {
                    switch ($update['key']) {
                        case 'name':
                            if (!DatabaseRepository::isValidColumnName($update['new'])) {
                                throw new InvalidColumnException($update['new']);
                            }
                            $schemaAlter->renameColumn($update['old'], $update['new']);
                            $column['column']['name'] = $update['new'];
                            break;
                        case 'type':
                            $column['column']['type'] = $update['new'];
                            $runChange = true;
                            break;
                        case 'length':
                            $column['column']['length'] = $update['new'];
                            $runChange = true;
                            break;
                        case 'default':
                            $column['column']['default'] = $update['new'];
                            $runChange = true;
                            break;
                        case 'isNull':
                            $column['column']['isNull'] = $update['new'];
                            $runChange = true;
                            break;
                        case 'isAutoIncrement':
                            $column['column']['isAutoIncrement'] = $update['new'];
                            $runChange = true;
                            break;
                    }
                }
                if ($runChange) {
                    $options = SchemaBuilder::parseColumnOptions($column['column']);
                    $columnType = SchemaBuilder::parseColumnType($column['column']['type'], $column['column']['length']);
                    $definition = SchemaBuilder::buildColumnDefinition($column['column']['name'], $columnType, $options);
                    $schemaAlter->changeColumnDefinition($column['column']['name'], $definition);
                }
            } elseif ($column['action'] === 'add') {
                $options = SchemaBuilder::parseColumnOptions($column['column']);
                $columnType = SchemaBuilder::parseColumnType($column['column']['type'], $column['column']['length']);
                $schemaAlter->addColumn($column['column']['name'], $columnType, $options);
            }
        }
    }

    public function deleteDatabase(string $databaseName): void
    {
        if (!$this->isValidDatabaseName($databaseName)) {
            throw new InvalidDatabaseException();
        }
        $this->getConnection()->query('DROP DATABASE ' . $databaseName);
    }

    public function deleteDatabaseTable(string $databaseName, string $tableName): void
    {
        if (!$this->isValidTableName($tableName)) {
            throw new InvalidTableException();
        }
        $this->useDatabase($databaseName)->getConnection()->query('DROP TABLE ' . $tableName);
    }

    public function runSql(?string $databaseName, string $sql): mixed
    {
        if ($databaseName === null) {
            $conn = $this->getConnection();
        } else {
            $conn = $this->useDatabase($databaseName)->getConnection();
        }
        $query = $conn->prepare($sql);
        $queryType = strtoupper(explode(' ', trim($sql))[0]);
        $executionSuccess = $query->execute();

        $result = match ($queryType) {
            'SELECT' => $query->fetchAll(\PDO::FETCH_ASSOC),
            'INSERT', 'UPDATE', 'DELETE' => $query->rowCount(),
            default => $executionSuccess,
        };

        return [
            'queryType' => $queryType,
            'result' => $result,
        ];
    }

    public function getAllUsers(): array
    {
        return $this->getConnection()->query("SELECT user, host, CASE WHEN password = '' THEN FALSE ELSE TRUE END as hasPassword FROM mysql.user")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllConnections(): array
    {
        return $this->getConnection()->query('SHOW FULL PROCESSLIST')->fetchAll(\PDO::FETCH_ASSOC);
    }
}
