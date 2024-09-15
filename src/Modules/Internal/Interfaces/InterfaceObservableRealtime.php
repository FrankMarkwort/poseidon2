<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;

use ErrorException;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Socket\Client;
use Nmea\Protocol\Socket\SocketException;

interface InterfaceObservableRealtime
{
    public function attach(InterfaceObserverRealtime $observer):self;
    public function detach(InterfaceObserverRealtime $observer);
    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     * @throws SocketException
    */
    public function notify():void;
    public function setFrame(Frame $frame, string $data, ?Client $webSocket = null):void;
    public function getFrame():Frame;
    public function getData():string;
    public function getWebSocket():?Client;
}