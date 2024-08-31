<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

use Nmea\Database\Database;
use Nmea\Database\Mapper\WindSpeedHoursMapper;
use Nmea\Config\Config;
header('Content-Type: application/json; charset=utf-8');

require_once( __DIR__ . '/../../../vendor/autoload.php');

Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());

$mapper = new WindSpeedHoursMapper(Database::getInstance());
echo $mapper->getAll()->toJson();
