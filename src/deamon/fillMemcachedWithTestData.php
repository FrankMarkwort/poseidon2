#!/usr/bin/php
<?php
declare(strict_types=1);
use Core\Cache\Memcached;
use Core\Deamon\Bootstrap;
use Core\Deamon\Serial;
use Core\Config\Config;
use Modules\Internal\RealtimeDistributor;
use Core\Protocol\Socket\Client;

require_once(__DIR__ . '/../../vendor/autoload.php');
$bootstrap = new Bootstrap(
        new Serial( __DIR__ . '/../../test/TestData/data.log'),
        (new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()))->clear(),
        new Client(Config::getSocketServerHost(),Config::getSocketServerPort()),
        new RealtimeDistributor()
);

$bootstrap->run();

