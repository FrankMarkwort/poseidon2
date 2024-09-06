<?php
declare(strict_types=1);

namespace Modules\AnchorWatch\Observer;


interface InterfaceObserver
{
    public function update(InterfaceObservable $observable):void;
}