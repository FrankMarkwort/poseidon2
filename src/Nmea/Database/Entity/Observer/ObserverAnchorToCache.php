<?php

namespace Nmea\Database\Entity\Observer;

use Nmea\Cache\Memcached;
use Nmea\Config\Config;

class ObserverAnchorToCache implements InterfaceObserver
{
    public function update(InterfaceObservable $observable)
    {
        if ($observable->isAnchorSet()) {
            $cache = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
            $cache->set('OBJ_ANCHOR', serialize($observable));
        }
    }
}

