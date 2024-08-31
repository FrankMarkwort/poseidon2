<?php
declare(strict_types=1);

namespace Nmea\Database\Entity\Observer;


interface InterfaceObserver
{
    public function update(InterfaceObservable $observable):void;
}