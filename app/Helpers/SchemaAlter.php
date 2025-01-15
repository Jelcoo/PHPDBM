<?php

namespace App\Helpers;

class SchemaAlter
{
    private \PDO $pdo;
    private string $table = '';

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function dropColumn(string $column): void
    {
        $query = "ALTER TABLE {$this->table} DROP COLUMN {$column}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }

    public function addColumn(string $name, string $type, array $options = []): void
    {
        $columnDefinition = SchemaBuilder::buildColumnDefinition($name, $type, $options);

        $query = "ALTER TABLE {$this->table} ADD COLUMN $columnDefinition";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }
}
