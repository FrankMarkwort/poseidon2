<?php
declare(strict_types=1);

namespace Nmea\Logger;

use Nmea\Logger\Logger\NullLogger;

class Logger implements LoggerAwareInterface
{
    private LoggerInterface $logger;
    public function __construct()
    {
        $this->logger = new NullLogger();
    }
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
