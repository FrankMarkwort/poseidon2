<?php

namespace Nmea\Cron;

use Amp\Cancellation;
use Amp\Sync\Channel;

class CronChannel implements Channel
{

    public function receive(?Cancellation $cancellation = null): mixed
    {
        // TODO: Implement receive() method.
    }

    public function send(mixed $data): void
    {
        // TODO: Implement send() method.
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

    public function isClosed(): bool
    {
        // TODO: Implement isClosed() method.
    }

    public function onClose(\Closure $onClose): void
    {
        // TODO: Implement onClose() method.
    }
}