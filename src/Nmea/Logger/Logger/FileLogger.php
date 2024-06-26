<?php

namespace Nmea\Logger\Logger;

use Nmea\Logger\AbstractLogger;

class FileLogger extends AbstractLogger
{

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $filename = __DIR__ . '/../../../logs/' . date('Y-m-d') . '.log';
        if (!file_exists($filename)) {
            touch($filename);
        }
        $fp = fopen($filename, 'a');
        fwrite($fp, "$level:  $message".PHP_EOL);
        fclose($fp);
    }
}