<?php

namespace Nmea\Database\Entity\Observer;


interface InterfaceObserver
{
    public function update(InterfaceObservable $observable);
}