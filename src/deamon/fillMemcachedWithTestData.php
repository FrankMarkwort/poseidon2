#!/usr/bin/php
<?php
declare(strict_types=1);
use Nmea\Cache\Memcached;
use Nmea\Deamon\Bootstrap;
use Nmea\Deamon\Serial;
use Nmea\Config\Config;
use Modules\Internal\RealtimeDistributor;
use Nmea\Protocol\Socket\Client;

require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(
        new Serial( __DIR__ . '/../../test/TestData/data.log'),
        (new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()))->clear(),
        new Client(Config::getSocketServerHost(),Config::getSocketServerPort()),
        new RealtimeDistributor()
);

$bootstrap->run();

