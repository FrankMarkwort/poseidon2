<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;


interface InterfaceObserverCronWorker
{
    public function update(InterfaceObservableCronWorker $observable):void;
}