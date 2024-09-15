<?php

use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
use Nmea\Config\Config;

$bootstrap = new Bootstrap(
    new Serial(__DIR__ . '/../../bin/ttyOut'),
    new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()),
    new \Nmea\Protocol\Socket\Client(Config::getSocketServerHost(), Config::getSocketServerPort()),
    new \Modules\Internal\RealtimeDistributor()
);
$bootstrap->run();
