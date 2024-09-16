#!/usr/bin/php
<?php
declare(strict_types=1);
set_time_limit(0);
use Core\Cache\Memcached;
use Core\Deamon\Bootstrap;
use Core\Deamon\Serial;
use Core\Config\Config;
use Core\Protocol\Socket\Client;
use Modules\Internal\RealtimeDistributor;
require_once(__DIR__ . '/../../vendor/autoload.php');
$register = include (__DIR__ . '/../Modules/register.php');

$bootstrap = new Bootstrap(
    new Serial(Config::getSerialDevice()), (new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()))->clear(),
    new Client(Config::getSocketServerHost(), Config::getSocketServerPort()),
    $register[RealtimeDistributor::class]()
);
$bootstrap->run();

