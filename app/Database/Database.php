<?php

namespace App\Database;

use PDO;
use App\Config\Config;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = $this->getPDO();
    }

    private static Database $database;

    public static function getInstance(): Database
    {
        if (!isset(self::$database)) {
            self::$database = new Database();
        }

        return self::$database;
    }

    private function getPDO(): PDO
    {
        if (isset($this->pdo)) {
            return $this->pdo;
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $hostname = Config::getKey('DB_HOST');
        $port = Config::getKey('DB_PORT');
        $user = Config::getKey('DB_USERNAME');
        $password = Config::getKey('DB_PASSWORD');
        $db = Config::getKey('DB_DATABASE');
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$hostname;port=$port;dbname=$db;charset=$charset";
        try {
            $_pdo = new PDO($dsn, $user, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        return $_pdo;
    }
}
