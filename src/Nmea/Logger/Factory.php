<?php
declare(strict_types=1);

namespace Nmea\Logger;

use Nmea\Logger\Logger\FileLogger;

class Factory
{
    private static LoggerInterface $instance;

    public static function log(string $message): void
    {
        if (!static::$instance instanceof LoggerInterface) {

            static::$instance = new FileLogger();
        }

        static::$instance->info($message);
    }
}