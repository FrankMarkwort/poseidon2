<?php

namespace Nmea\Database;

use \PDO;

class Database implements DatabaseInterface
{
    private PDO $pdo;
    private static $instance = null;

    public function init(string $host, string $user, string $pass, string $db): void
    {
        $this->pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
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
        return $this->getPdo()->exec($sql);
    }

    public function query(string $sql):array
    {
        return $this->getPdo()->query($sql)->fetchAll();
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

