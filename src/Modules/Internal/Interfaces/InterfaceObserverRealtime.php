<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;


use ErrorException;
use Core\Config\ConfigException;
use Core\Parser\ParserException;
use Core\Protocol\Socket\SocketException;

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