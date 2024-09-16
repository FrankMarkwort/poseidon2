<?php
declare(strict_types=1);

namespace Core\Logger;

interface LoggerAwareInterface
{
    public function setLogger(LoggerInterface $logger): self;
    public function getLogger(): LoggerInterface;
}