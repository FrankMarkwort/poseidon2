<?php

namespace Nmea\Cron;

use Amp\Parallel\Worker\Task;
use Nmea\Cache\CacheInterface;
use Amp\Sync\Channel;
use Amp\Cancellation;

class CronWorker implements Task
{
    public function __construct(private readonly int $sleepTime, private readonly array $pgns,private readonly CacheInterface $cache)
    {
    }
    public function run(Channel $channel, Cancellation $cancellation):mixed
    {
        return $channel->receive();
    }
}