<?php

namespace Modules\Internal;

use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\Interfaces\InterfaceObserverRealtime;
use Nmea\Protocol\Frames\Frame\Frame;

class RealtimeDistributor implements InterfaceObservableRealtime
{
    private Frame $frame;
    private array $observers = [];

    public function setFrame(Frame $frame):void
    {
        $this->frame = $frame;
        $this->notify();
    }

    public function getFrame():Frame
    {
        return $this->frame;
    }

    public function attach(InterfaceObserverRealtime $observer):void
    {
        $this->observers[] = $observer;
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
}