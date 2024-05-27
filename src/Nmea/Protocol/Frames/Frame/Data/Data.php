<?php


namespace Nmea\Protocol\Frames\Frame\Data;


class Data
{
    private const string DELIMITTER =' ';

    public function __construct(private readonly string $data, private readonly string $direction, private readonly string $timestamp)
    {
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getDataBytes(): array
    {
        return explode(self::DELIMITTER, $this->getData());
    }

    public function getData():string
    {
        return $this->data;
    }

    public function getFirstByte(): string
    {
        $bytes = explode(self::DELIMITTER, $this->getData());

        return $bytes[0];
    }

    public function getSecondByte(): string
    {
         $bytes = explode(self::DELIMITTER, $this->getData());

        return $bytes[1];
    }
}