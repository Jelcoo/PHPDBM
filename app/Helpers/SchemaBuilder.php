<?php

namespace App\Helpers;

class SchemaBuilder
{
    private \PDO $pdo;
    private string $table = '';
    private array $columns = [];
    private string $primaryKey = '';
    private array $modifications = [];
    private string $query = '';

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function create(): self
    {
        $this->query = "CREATE TABLE {$this->table} (";
        return $this;
    }

    public function drop(): void
    {
        $sql = "DROP TABLE IF EXISTS {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    public function addColumn(string $name, string $type, array $options = []): self
    {
        $this->columns[] = $this->buildColumnDefinition($name, $type, $options);
        return $this;
    }

    public function primaryKey(string $column): self
    {
        $this->primaryKey = $column;
        return $this;
    }

    public function alter(): self
    {
        $this->query = "ALTER TABLE {$this->table} ";
        return $this;
    }

    public function modifyColumn(string $name, string $type, array $options = []): self
    {
        $definition = $this->buildColumnDefinition($name, $type, $options);
        $this->modifications[] = "MODIFY COLUMN $definition";
        return $this;
    }

    public function dropColumn(string $name): self
    {
        $this->modifications[] = "DROP COLUMN $name";
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

        if (!empty($this->modifications)) {
            $this->query .= implode(", ", $this->modifications);
        }

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute();

        $this->reset();
    }

    private function buildColumnDefinition(string $name, string $type, array $options): string
    {
        $definition = "`$name` $type";

        if (!empty($options['default'])) {
            $definition .= " DEFAULT " . (is_string($options['default']) ? "'{$options['default']}'" : $options['default']);
        }

        if (!empty($options['nullable']) && $options['nullable'] === false) {
            $definition .= " NOT NULL";
        }

        if (!empty($options['auto_increment']) && $options['auto_increment'] === true) {
            $definition .= " AUTO_INCREMENT";
        }

        return $definition;
    }

    private function reset(): void
    {
        $this->table = '';
        $this->columns = [];
        $this->primaryKey = '';
        $this->modifications = [];
        $this->query = '';
    }
}
