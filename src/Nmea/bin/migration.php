#!/usr/bin/php
<?php

use Nmea\Database\Database;
use Nmea\Migration\Migration;

require_once(__DIR__ . '/../../../vendor/autoload.php');

Database::getInstance()->init('172.17.0.1', 'nmea2000', 'nmea2000', 'nmea2000');

(new Migration)->run();
