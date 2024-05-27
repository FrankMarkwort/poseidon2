<?php

use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;

$bootstrap = new Bootstrap(new Serial(__DIR__ . '/../../bin/ttyOut'), new Memcached());
$bootstrap->run();
