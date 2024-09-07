<?php
declare(strict_types=1);

namespace Modules\AnchorWatch\Observer;

interface InterfaceObservable
{
    public function attach(InterfaceObserver $observer);
    public function detach(InterfaceObserver $observer);
    public function notify();
}