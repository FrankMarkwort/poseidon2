<?php

use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;

$bootstrap = new Bootstrap(new Serial(__DIR__ . '/../../bin/ttyOut'), new Memcached('172.17.0.1'));
$bootstrap->run();
