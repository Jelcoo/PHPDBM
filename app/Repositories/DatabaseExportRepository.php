<?php

namespace App\Repositories;

class DatabaseExportRepository
{
    private string $host = '';
    private string $port = '';
    private string $username = '';
    private string $password = '';
    private string $database = '';
    private string $table = '';

    public function prepare(string $ipAddress = '', string $port = '', string $username = '', string $password = '', string $database = '', string $table = ''): void
    {
        $this->host = $ipAddress;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->table = $table;
    }

    public function exportDatabase(): string
    {
        $now = date('Y-m-d H:i:s');
        $exportName = "Export of {$this->database} $now.sql";
        $dir = __DIR__ . '/../../storage/exports';
        $fullPath = $dir . '/' . $exportName;

        \Spatie\DbDumper\Databases\MySql::create()
            ->setSkipSsl(true)
            ->setHost($this->host)
            ->setPort((int) $this->port)
            ->setUserName($this->username)
            ->setPassword($this->password)
            ->setDbName($this->database)
            ->dumpToFile($fullPath);

        return $fullPath;
    }

    public function exportTable(): string
    {
        $now = date('Y-m-d H:i:s');
        $exportName = "Export of {$this->table} $now.sql";
        $dir = __DIR__ . '/../../storage/exports';
        $fullPath = $dir . '/' . $exportName;

        \Spatie\DbDumper\Databases\MySql::create()
            ->setSkipSsl(true)
            ->setHost($this->host)
            ->setPort((int) $this->port)
            ->setUserName($this->username)
            ->setPassword($this->password)
            ->setDbName($this->database)
            ->includeTables([$this->table])
            ->dumpToFile($fullPath);

        return $fullPath;
    }
}
