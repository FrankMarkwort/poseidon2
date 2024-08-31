#!/usr/bin/php
<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../vendor/autoload.php');
use Nmea\Protocol\Socket\Server;
use Nmea\Config\Config;
use Nmea\Protocol\Socket\SocketsCollection;
use Nmea\Protocol\Socket\MessageHandler;
$socedCollection = new SocketsCollection();
$socetServer = new Server(Config::getSocketServerHost(), Config::getSocketServerPort(),  $socedCollection, new MessageHandler($socedCollection));
$socetServer->run();