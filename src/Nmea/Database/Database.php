<?php

namespace Nmea\Database;

use \PDO;

class Database implements DatabaseInterface
{
    private PDO $pdo;
    private static $instance = null;

    public function init(string $host, int $port, string $user, string $pass, string $db): void
    {
        $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    }

    public static function getInstance():self
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function execute(string $sql):int|false
    {
        try {
            return $this->getPdo()->exec($sql);
        } catch (\PDOException $e) {
             throw new \Exception('exec' . $sql, $e->getCode(), $e);
        }
    }

    public function query(string $sql):array
    {
        try {
            return $this->getPdo()->query($sql)->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception('query' . $sql, $e->getCode(), $e);
        }
    }

    private function getPdo():PDO
    {
        return $this->pdo;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}

