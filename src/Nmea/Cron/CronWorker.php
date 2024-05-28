<?php

namespace Nmea\Cron;

use Nmea\Cache\CacheInterface;

class CronWorker
{
    public function __construct(private readonly int $sleepTime, private readonly array $pgns,private readonly CacheInterface $cache)
    {
    }
    public function run()
    {
        foreach ($this->pgns as $pgn) {
            $data = $this->cache->get($pgn);
        }
    }
}