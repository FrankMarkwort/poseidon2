#!/usr/bin/php
<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../vendor/autoload.php');
use Core\Protocol\Socket\Server;
use Core\Config\Config;
use Core\Protocol\Socket\SocketsCollection;
use Core\Protocol\Socket\MessageHandler;
$socketCollection = new SocketsCollection();
$socketServer = new Server(Config::getSocketServerHost(), Config::getSocketServerPort(),  $socketCollection, new MessageHandler($socketCollection));
$socketServer->run();