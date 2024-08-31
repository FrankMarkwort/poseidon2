<?php

declare(strict_types=1);

namespace Nmea\Service;

readonly class Service
{
    public function __construct(private string $serviceName)
    {
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function isRunning(): bool
    {
        exec("sudo systemctl status ". $this->getServiceName(), $pid, $code);
        if ($code === 0) {

            return true;
        }

        return false;
    }

    public function start(): void
    {
        exec('sudo systemctl start ' . $this->getServiceName());
        sleep(2);
    }

    public function stop(): void
    {
        exec("sudo systemctl stop " . $this->getServiceName());
        sleep(2);
    }

    public function nextStatus(): string
    {
        if ($this->isRunning()) {

            return 'stop';
        }

        return 'start';
    }
}