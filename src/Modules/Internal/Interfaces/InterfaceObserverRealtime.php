<?php
declare(strict_types=1);

namespace Modules\Internal\Interfaces;


interface InterfaceObserverRealtime
{
    public function update(InterfaceObservableRealtime $observable):void;
}