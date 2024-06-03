#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
use Nmea\Config\Config;
require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(new Serial(Config::getSerialDevice()), (new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()))->clear());

$bootstrap->run();

