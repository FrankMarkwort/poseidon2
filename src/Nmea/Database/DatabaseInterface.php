<?php

namespace Nmea\Database;

interface DatabaseInterface
{
    public function init(string $host, string $user, string $pass, string $db):void;
    public static function getInstance():self;
    public function execute(string $sql):int|false;
    public function query(string $sql):array;
}