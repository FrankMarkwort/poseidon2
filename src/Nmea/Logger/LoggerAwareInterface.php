<?php
declare(strict_types=1);

namespace Nmea\Logger;

interface LoggerAwareInterface
{
    public function setLogger(LoggerInterface $logger): self;
    public function getLogger(): LoggerInterface;
}