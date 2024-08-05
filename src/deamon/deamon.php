#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
use Nmea\Config\Config;
use Nmea\Protocol\Socket\Client;
require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(
    new Serial(Config::getSerialDevice()), (new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()))->clear(),
    new Client(Config::getSocketServerHost(), Config::getSocketServerPort())
);
$bootstrap->run();

