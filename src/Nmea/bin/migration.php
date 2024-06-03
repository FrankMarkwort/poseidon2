#!/usr/bin/php
<?php

use Nmea\Database\Database;
use Nmea\Migration\Migration;
use Nmea\Config\Config;

require_once(__DIR__ . '/../../../vendor/autoload.php');

Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());
#'192.168.0.101', 'nmea2000', 'nmea2000', 'nmea2000');

(new Migration)->run();
