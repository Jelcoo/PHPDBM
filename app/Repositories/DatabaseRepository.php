<?php

namespace App\Repositories;

use App\Application\Response;
use App\Application\Session;
use App\Config\Config;
use App\Controllers\ErrorController;
use App\Exceptions\InvalidDatabaseException;

class DatabaseRepository
{
    private \PDO $pdoConnection;
    private string $host = '';
    private string $port = '';
    private string $username = '';
    private string $password = '';

    public string $database = '';
    public string $table = '';

    public function useDatabase(string $database): self
    {
        if (! $this->isValidDatabaseName($database)) {
            throw new InvalidDatabaseException();
        }
        $this->database = $database;
        $this->useDB($database);

        return $this;
    }
    public function useTable(string $table): self
    {
        if (! $this->isValidTableName($table)) {
            throw new InvalidDatabaseException();
        }
        $this->table = $table;
        
        return $this;
    }

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
        if (Session::isValidSession()) {
            $this->prepare($_SESSION['ip_address'], $_SESSION['port'], $_SESSION['username'], $_SESSION['password']);
        }
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
        
        try {
            $this->pdoConnection = new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            if (Config::getKey('APP_ENV') === 'development') {
                throw $e;
            }
            $response = new Response();
            $response->setStatusCode(500);
            $response->setContent((new ErrorController())->error500($e->getMessage()));
            $response->send();
            exit;
        }

        return $this->pdoConnection;
    }

    private function useDB(string $databaseName): void
    {
        $this->getConnection()->exec("USE `$databaseName`");
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
