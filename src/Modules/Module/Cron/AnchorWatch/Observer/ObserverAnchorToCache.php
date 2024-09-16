<?php
declare(strict_types=1);

namespace Modules\Module\Cron\AnchorWatch\Observer;

use Exception;
use Modules\Module\Cron\AnchorWatch\Anchor;
use Core\Cache\Memcached;
use Core\Config\Config;

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

