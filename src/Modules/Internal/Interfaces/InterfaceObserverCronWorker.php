<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;

use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

interface InterfaceObserverCronWorker
{
    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function update(InterfaceObservableCronWorker $observable):void;

    public function isRunEveryMinute(): bool;
}