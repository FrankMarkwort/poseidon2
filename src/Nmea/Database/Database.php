<?php
declare(strict_types=1);

namespace Nmea\Database;

use Exception;
use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    private PDO $pdo;
    private static self|null $instance = null;

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

    /**
     * @throws Exception
     */
    public function execute(string $sql):int|false
    {
        try {
            return $this->getPdo()->exec($sql);
        } catch (PDOException $e) {
             throw new Exception('exec' . $sql, $e->getCode(), $e);
        }
    }

    /**
     * @throws Exception
     */
    public function query(string $sql):array
    {
        try {
            return $this->getPdo()->query($sql)->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('query' . $sql, $e->getCode(), $e);
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

