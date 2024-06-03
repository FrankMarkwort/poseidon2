#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Database\Database;
use Nmea\Config\Config;
use Nmea\Cron\CronWorker;

require_once(__DIR__ . '/../../vendor/autoload.php');
Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());
$worker = new CronWorker(60, Database::getInstance(), new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()));
$worker->run();
