#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
require_once(__DIR__ . '/../../vendor/autoload.php');
$worker[60] = new \Nmea\Cron\CronWorker(60, [130306], new Memcached());
$worker[60]->run();
