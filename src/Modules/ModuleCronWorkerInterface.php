<?php

namespace Modules;

use Nmea\Cache\CacheInterface;

interface ModuleCronWorkerInterface
{
    public function isReady():bool;
    public function run():void;
    public function setEveryMinute(bool $minute = false):void;

    public function getCache():CacheInterface;
}