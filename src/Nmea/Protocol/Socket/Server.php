<?php
declare(strict_types=1);

namespace Nmea\Protocol\Socket;

class Server
{
    private $socketResource;
    public function __construct (
        private readonly string $host,
        private readonly int $port,
        private SocketsCollection $clientSockets,
        private readonly HandleInterface $messageHandler
    )
    {
        $this->socketResource = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->socketResource, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->socketResource, $this->host, $this->port);
        socket_listen($this->socketResource);
        $this->clientSockets[] = $this->socketResource;
    }

    public function run():void
    {
        $dummy = null;
        while (true) {
            $newSocketArray = $this->clientSockets->toArray();
            socket_select($newSocketArray, $dummy, $dummy, 0, 10);
            if (in_array($this->socketResource, $newSocketArray)) {
                $newSocket = socket_accept($this->socketResource);
                $this->clientSockets[] = $newSocket;
                $header = socket_read($newSocket, 1024);
                $this->messageHandler->doHandshake($header, $newSocket, $this->host, $this->port);
                @socket_getpeername($newSocket, $clientIpAddress);
                $connectionAck = $this->messageHandler->newConnectionAck($clientIpAddress);
                $this->messageHandler->send($connectionAck);
                $newSocketIndex = array_search($this->socketResource, $newSocketArray);
                unset($newSocketArray[$newSocketIndex]);
            }

            foreach ($newSocketArray as $newSocketArrayResource) {
                while (@socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1) {
                    $socketMessage = $this->messageHandler->unseal($socketData);
                    $this->messageHandler->send($this->messageHandler->createMessage($socketMessage));
                    break 2;
                }
                $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
                if ($socketData === false) {
                    @socket_getpeername($newSocketArrayResource, $clientIpAddress);
                    $connectionAck = $this->messageHandler->connectionDisconnectAck($clientIpAddress);
                    $this->messageHandler->send($connectionAck);
                    $newSocketIndex = array_search($newSocketArrayResource, $this->clientSockets->toArray());
                    unset($this->clientSockets[$newSocketIndex]);
                }
            }
        }
    }

    public function __destruct()
    {
        socket_close($this->socketResource);
    }
}