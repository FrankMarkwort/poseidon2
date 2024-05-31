#!/usr/bin/php
<?php

use Nmea\Database\Database;
use Nmea\Migration\Migration;

require_once(__DIR__ . '/../../../vendor/autoload.php');

Database::getInstance()->init('192.168.0.101', 'nmea2000', 'nmea2000', 'nmea2000');

(new Migration)->run();
