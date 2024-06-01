<?php
use Nmea\Database\Database;
use Nmea\Database\Mapper\WindSpeedHoursMapper;
header('Content-Type: application/json; charset=utf-8');

require_once( __DIR__ . '/../../vendor/autoload.php');

Database::getInstance()->init('127.0.0.1', 'nmea2000', 'nmea2000', 'nmea2000');
$mapper = new WindSpeedHoursMapper(Database::getInstance());
echo $mapper->getAll()->toJson();
