<?php
declare(strict_types=1);

namespace Modules\AnchorWatch\Observer;

use Exception;
use Nmea\Cache\Memcached;
use Nmea\Config\Config;
use Modules\AnchorWatch\Anchor;

class ObserverAnchorToCache implements InterfaceObserver
{
    private Memcached $cache;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->cache = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
    }
    /**
     * @throws Exception
     * @param Anchor $observable
     */
    public function update(InterfaceObservable $observable):void
    {
        if ($observable->isAnchorSet()) {
            $this->cache->set('OBJ_ANCHOR', serialize($observable));
        } elseif ($observable->getChainLength() === 0 && $this->cache->isSet('OBJ_ANCHOR')) {
            $this->cache->delete('OBJ_ANCHOR');
        }
    }
}
