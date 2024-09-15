<?php

namespace Modules\Internal;

use ErrorException;
use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\Interfaces\InterfaceObserverRealtime;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Socket\Client;
use Nmea\Protocol\Socket\SocketException;

class RealtimeDistributor implements InterfaceObservableRealtime
{
    private Frame $frame;
    private array $observers = [];
    private string $data;
    private ?Client $webSocket = null;

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     * @throws SocketException
     */
    public function setFrame(Frame $frame, string $data, ?Client $webSocket = null):void
    {
        $this->frame = $frame;
        $this->data = $data;
        $this->webSocket = $webSocket;
        $this->notify();
    }

    public function getFrame():Frame
    {
        return $this->frame;
    }

    public function attach(InterfaceObserverRealtime $observer):self
    {
        $this->observers[] = $observer;

        return $this;
    }

    public function detach(InterfaceObserverRealtime $observer):void
    {
        $this->observers = array_diff($this->observers, array($observer));
    }

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     * @throws SocketException
     */
     public function notify():void
     {
        foreach ($this->observers as $observer) {
            /**
             * @var $observer InterfaceObserverRealtime
             */
            $observer->update($this);
        }
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getWebSocket(): ?Client
    {
        return $this->webSocket;
    }
}