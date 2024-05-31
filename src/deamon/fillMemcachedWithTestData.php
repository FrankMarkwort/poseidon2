#!/usr/bin/php
<?php

use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(new Serial( __DIR__ . '/../../test/TestData/data.log'), (new Memcached('172.17.0.1'))->clear());

$bootstrap->run();

