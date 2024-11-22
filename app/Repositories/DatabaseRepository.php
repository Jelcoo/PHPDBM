<?php

namespace App\Repositories;

class DatabaseRepository
{
    private \PDO $pdoConnection;
    private string $host = '';
    private string $port = '';
    private string $username = '';
    private string $password = '';

    public function prepare(string $ipAddress = '', string $port = '', string $username = '', string $password = ''): void
    {
        $this->host = $ipAddress;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    public function prepareFromSession(): void
    {
        $this->prepare($_SESSION['ip_address'], $_SESSION['port'], $_SESSION['username'], $_SESSION['password']);
    }

    public function getConnection(): \PDO
    {
        return $this->connect();
    }

    private function connect(): \PDO
    {
        if (isset($this->pdoConnection)) {
            return $this->pdoConnection;
        }

        $dsn = "mysql:host=$this->host;port=$this->port;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdoConnection = new \PDO($dsn, $this->username, $this->password, $options);

        return $this->pdoConnection;
    }

    protected function useDatabase(string $databaseName): bool
    {
        if (! $this->isValidDatabaseName($databaseName)) {
            return false;
        }
        $this->getConnection()->exec("USE `$databaseName`");

        return true;
    }

    public static function isValidDatabaseName(string $databaseName): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $databaseName);
    }

    public static function isValidTableName(string $tableName): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $tableName);
    }
}
