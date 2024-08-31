#!/usr/bin/php
<?php
declare(strict_types=1);

use Nmea\Database\Database;
use Nmea\Migration\Migration;
use Nmea\Config\Config;

require_once(__DIR__ . '/../../../vendor/autoload.php');

Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());

(new Migration)->run();
