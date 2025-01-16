<?php

namespace App\Helpers;

use App\Exceptions\InvalidTableException;

class SchemaBuilder
{
    private \PDO $pdo;
    private string $table = '';
    private array $columns = [];
    private string $primaryKey = '';
    private string $query = '';

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table(string $table): self
    {
        if (empty($table)) {
            throw new InvalidTableException();
        }
        $this->table = $table;
        return $this;
    }

    public function create(): self
    {
        $this->query = "CREATE TABLE {$this->table} (";
        return $this;
    }

    public function addColumn(string $name, string $type, array $options = []): self
    {
        $this->columns[] = $this->buildColumnDefinition($name, $type, $options);
        return $this;
    }

    public function execute(): void
    {
        if (!empty($this->columns)) {
            $this->query .= implode(", ", $this->columns);
            if ($this->primaryKey) {
                $this->query .= ", PRIMARY KEY ({$this->primaryKey})";
            }
            $this->query .= ")";
        }

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();

        $this->reset();
    }

    public static function buildColumnDefinition(string $name, string $type, array $options): string
    {
        $definition = "`$name` $type";

        if (!empty($options['default'])) {
            $default = $options['default'];
            if ($default === 'CURRENT_TIMESTAMP') {
                $definition .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $definition .= " DEFAULT " . (is_string($options['default']) ? "'{$options['default']}'" : $options['default']);
            }
        }

        if (isset($options['nullable']) && $options['nullable'] === false) {
            $definition .= " NOT NULL";
        }

        if (!empty($options['auto_increment']) && $options['auto_increment'] === true) {
            $definition .= " AUTO_INCREMENT PRIMARY KEY";
        }

        return $definition;
    }

    public static function parseColumnOptions(array $column): array
    {
        $options = [];
        if ($column['default'] !== null) {
            $options['default'] = $column['default'];
        }
        if ($column['isNull'] === false) {
            $options['nullable'] = false;
        }
        if ($column['isAutoIncrement'] === true) {
            $options['auto_increment'] = true;
        }
        return $options;
    }
    
    public static function parseColumnType(string $type, string $length): string
    {
        $columnType = $type;
        if (!empty($length)) {
            $columnType .= '(' . $length . ')';
        }
        return $columnType;
    }

    private function reset(): void
    {
        $this->table = '';
        $this->columns = [];
        $this->primaryKey = '';
        $this->query = '';
    }
}
