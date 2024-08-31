<?php

namespace Nmea\Logger;

use Nmea\Logger\Logger\FileLogger;

class Factory
{
    private static $instance;

    public static function log(string $message)
    {
        if (!static::$instance instanceof LoggerInterface) {

            static::$instance = new FileLogger();
        }

        static::$instance->info($message);
    }
}