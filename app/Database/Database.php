<?php

namespace App\Database;

class Database
{
    private \PDO $pdoConnection;
    private string $host = '';
    private string $port = '';
    private string $username = '';
    private string $password = '';

    private static Database $database;

    public static function getInstance(): Database
    {
        if (!isset(self::$database)) {
            self::$database = new Database();
        }

        return self::$database;
    }

    public function prepare(string $ipAddress = '', string $port = '', string $username = '', string $password = '')
    {
        $this->host = $ipAddress;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
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
}
