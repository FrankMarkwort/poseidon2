#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use \Nmea\Database\Database;
use Nmea\Deamon\Serial;

require_once(__DIR__ . '/../../vendor/autoload.php');
Database::getInstance()->init('172.17.0.1', 'nmea2000', 'nmea2000', 'nmea2000');
$worker[60] = new \Nmea\Cron\CronWorker(60, Database::getInstance(), new Memcached('172.17.0.1'));
$worker[60]->run();
