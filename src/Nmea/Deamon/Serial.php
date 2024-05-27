<?php

namespace Nmea\Deamon;

class Serial
{
    private $streamResource;

    public function __construct(private readonly string $deviceName, private readonly string $mode = 'r')
    {
    }
    public function readStream():string
    {
        $result = fgets($this->streamResource);
        if ($result === false) {
            throw new \Exception();
        }

        return $this->removeSpecialCharacter($result);
    }

    public function open():void
    {
        $this->streamResource = fopen($this->deviceName, $this->mode);
    }

    public function close():void
    {
        fclose($this->streamResource);
    }

    private function removeSpecialCharacter(string $nmea2000):string
    {
        return str_replace(["\r", "\n"], '', $nmea2000);
    }
}