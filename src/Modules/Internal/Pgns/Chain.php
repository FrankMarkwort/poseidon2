<?php

namespace Modules\Internal\Pgns;

use Nmea\Cache\CacheInterface;

readonly class Chain
{
    private const string CHAIN_LENGTH = 'chain_length';
    public function __construct(private CacheInterface $cache , private bool $printToConsole = false)
    {
    }

    public function getLength():int
    {
        $chaneLength = $this->cache->get(static::CHAIN_LENGTH);
        if ($chaneLength === false) {
            $chaneLength = 0;
        }
        if ($this->isDebug()) {
            echo "chain length: " . $chaneLength .PHP_EOL;
        }

        return $chaneLength;
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