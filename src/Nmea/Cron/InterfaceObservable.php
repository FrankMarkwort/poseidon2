<?php
declare(strict_types=1);

namespace Nmea\Cron;

use Modules\AnchorWatch\Observer\InterfaceObserver;

interface InterfaceObservable
{
    public function attach(InterfaceObserver $observer);
    public function detach(InterfaceObserver $observer);
    public function notify();
}