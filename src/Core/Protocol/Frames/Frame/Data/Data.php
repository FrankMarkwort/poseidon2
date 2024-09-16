<?php
declare(strict_types=1);


namespace Core\Protocol\Frames\Frame\Data;


readonly class Data
{
    private const string DELIMITTER =' ';

    public function __construct(private string $data, private string $direction, private string $timestamp)
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