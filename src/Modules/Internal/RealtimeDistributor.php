<?php

namespace Modules\Internal;

use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\Interfaces\InterfaceObserverRealtime;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Socket\Client;

class RealtimeDistributor implements InterfaceObservableRealtime
{
    private Frame $frame;
    private array $observers = [];
    private $data;
    private ?Client $webSocket = null;

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