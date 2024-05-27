<?php

namespace Nmea\Cron;


use Nmea\Cache\Memcached as Cache;

class Cron
{
    private array $pgnEveryOneMinute = [];
    private array $privEveryMinuteTimestamp = [];

    public function run()
    {
        $cache = new Cache();
        foreach (array_keys($this->pgnEveryOneMinute) as $key ) {
            $nmea2000 = $cache->get($key);
            #$this->privEveryMinuteTimestamp[$key] =

        }
    }
}