<?php

namespace Nmea\Logger\Logger;

use Nmea\Logger\LoggerInterface;

class NullLogger implements LoggerInterface
{

    public function emergency(string $message, array $context = array()): void
    {
    }

    public function alert(string $message, array $context = array()): void
    {
    }

    public function critical(string $message, array $context = array()): void
    {
    }

    public function error(string $message, array $context = array()): void
    {
    }

    public function warning(string $message, array $context = array()): void
    {
    }

    public function notice(string $message, array $context = array()): void
    {
    }

    public function info(string $message, array $context = array()): void
    {
    }

    public function debug(string $message, array $context = array()): void
    {
    }

    public function log(mixed $level, string $message, array $context = array()): void
    {
    }
}