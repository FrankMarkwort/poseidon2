<?php

use Core\Cache\Memcached;
use Core\Deamon\Bootstrap;
use Core\Deamon\Serial;
use Core\Config\Config;

$bootstrap = new Bootstrap(
    new Serial(__DIR__ . '/../../bin/ttyOut'),
    new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()),
    new \Core\Protocol\Socket\Client(Config::getSocketServerHost(), Config::getSocketServerPort()),
    new \Modules\Internal\RealtimeDistributor()
);
$bootstrap->run();
