<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;

use Core\Config\ConfigException;
use Core\Parser\ParserException;

interface InterfaceObserverCronWorker
{
    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function update(InterfaceObservableCronWorker $observable):void;

    public function isRunEveryMinute(): bool;
}