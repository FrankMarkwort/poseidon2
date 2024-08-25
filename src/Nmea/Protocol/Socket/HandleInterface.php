<?php

namespace Nmea\Protocol\Socket;

use Socket;
interface HandleInterface
{
      public function send(mixed $message):true;
      public function unseal(false|string|array $socketData):false|string|array;
      public function seal(string $socketData):string;
      public function doHandshake(string $receivedHeader, Socket $clientSocketResource, string $hostName, int $port):void;
      public function newConnectionAck(string $clientIpAddress):string;
      public function connectionDisconnectAck(string $clientIpAddress):string;
      public function createMessage(string $jsonMessage):string;
}