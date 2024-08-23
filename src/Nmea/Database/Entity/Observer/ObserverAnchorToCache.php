<?php

namespace Nmea\Database\Entity\Observer;

use Nmea\Cache\Memcached;
use Nmea\Config\Config;
use Nmea\Database\Entity\Anchor;

class ObserverAnchorToCache implements InterfaceObserver
{
    /**
     * @throws \Exception
     * @param Anchor $observable
     */
    public function update(InterfaceObservable $observable):void
    {
        //TODO cache static ??
        $cache = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
        if ($observable->isAnchorSet()) {
            $cache->set('OBJ_ANCHOR', serialize($observable));
        } elseif ($observable->getChainLength() === 0 && $cache->isSet('OBJ_ANCHOR')) {
            $cache->delete('OBJ_ANCHOR');
        }
    }
}

