#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(new Serial( '/dev/ttyACM0'), (new Memcached())->clear());

$bootstrap->run();

