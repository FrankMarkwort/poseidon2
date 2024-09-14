<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;

use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Socket\Client;

interface InterfaceObservableRealtime
{
    public function attach(InterfaceObserverRealtime $observer):self;
    public function detach(InterfaceObserverRealtime $observer);
    public function notify():void;
    public function setFrame(Frame $frame, string $data, ?Client $webSocket = null):void;
    public function getFrame():Frame;
    public function getData():string;
    public function getWebSocket():?Client;
}