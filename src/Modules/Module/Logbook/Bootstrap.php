<?php

namespace Modules\Module\Logbook;

use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;

class Bootstrap implements InterfaceObserverCronWorker
{

    public function update(InterfaceObservableCronWorker $observable): void
    {
        // TODO: Implement update() method.
    }
}