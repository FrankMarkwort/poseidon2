<?php

namespace Nmea\Logger;

interface LoggerAwareInterface
{
    public function setLogger(LoggerInterface $logger): self;
    public function getLogger(): LoggerInterface;
}