<?php
declare(strict_types=1);

namespace Core\Database;

interface DatabaseInterface
{
    public function init(string $host, int $port, string $user, string $pass, string $db):void;
    public static function getInstance():self;
    public function execute(string $sql):int|false;
    public function query(string $sql):array;
}