<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;


use ErrorException;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;
use Nmea\Protocol\Socket\SocketException;

interface InterfaceObserverRealtime
{
    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     * @throws SocketException
     */
    public function update(InterfaceObservableRealtime $observable):void;
}