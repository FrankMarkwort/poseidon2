<?php

namespace Modules\Internal\Pgns;

use Core\Cache\CacheInterface;

readonly class Chain
{
    private const string CHAIN_LENGTH = 'chain_length';
    public function __construct(private CacheInterface $cache , private bool $printToConsole = false)
    {
    }

    public function getLength():int
    {
        $chainLength = $this->cache->get(static::CHAIN_LENGTH);
        if ($chainLength === false) {
            $chainLength = 0;
        }
        if ($this->isDebug()) {
            echo "chain length: " . $chainLength .PHP_EOL;
        }

        return $chainLength;
    }

    protected function isDebug():bool
    {
        return $this->printToConsole;
    }

    public function isSet():bool
    {
        return $this->cache->isSet(static::CHAIN_LENGTH) && $this->cache->get(static::CHAIN_LENGTH) > 0;
    }

    public function removeFromCache():void
    {
        $this->cache->delete('OBJ_ANCHOR');
    }
}