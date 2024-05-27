<?php

namespace Nmea\Database;

use \PDO;

class Database
{
    private PDO $pdo;
    private static $instance = null;


    public static function getInstance():self
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *
     */
    public function init($host, $user, $pass, $db)
    {
        $this->pdo = new PDO("mysql:host=$host;port=3306;dbname=$db", $user, $pass);
    }

    /**
     *
     */
    public function execute($sql):int|false
    {
        return $this->getPdo()->exec($sql);
    }

    public function query($sql):array
    {
        return $this->getPdo()->query($sql)->fetchAll();
    }

    public function getPdo():PDO
    {
        return $this->pdo;
    }

    private function __construct()
    {
    }

    private function __clone(){

    }


}

