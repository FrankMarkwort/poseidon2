<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;

use Nmea\Cache\CacheInterface;
use Nmea\Database\DatabaseInterface;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Frames\Frames;

interface InterfaceObservableRealtime
{
    public function attach(InterfaceObserverRealtime $observer);
    public function detach(InterfaceObserverRealtime $observer);
    public function notify():void;

    public function setFrame(Frame $frame):void
    public function getFrame():Frame;
}