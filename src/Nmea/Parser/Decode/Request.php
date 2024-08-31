<?php
declare(strict_types=1);

namespace Nmea\Parser\Decode;

class Request
{
    private int $bitStart;
    private int $bitOffset;
    private int $bitLength;
    private float|int|null $resolution;
    private bool $signet;
    private string|null $type;
    private float|int|null $rangeMin;
    private float|int|null $rangeMax;

    public function getBitStart(): int
    {
        return $this->bitStart;
    }

    public function setBitStart(int $bitStart): Request
    {
        $this->bitStart = $bitStart;
        return $this;
    }

    public function getBitOffset(): int
    {
        return $this->bitOffset;
    }
    public function setBitOffset(int $bitOffset): Request
    {
        $this->bitOffset = $bitOffset;
        return $this;
    }


    public function getResolution(): float|int|null
    {
        return $this->resolution;
    }

    public function setResolution(?float $resolution): Request
    {
        $this->resolution = $resolution;
        return $this;
    }

    public function getSignet(): bool
    {
        return $this->signet;
    }

    public function setSignet(bool $signet): Request
    {
        $this->signet = $signet;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Request
    {
        $this->type = $type;
        return $this;
    }

    public function getRangeMin():int|float|null
    {
        return $this->rangeMin;
    }

    public function setRangeMin(int|float|null $rangeMin): Request
    {
        $this->rangeMin = $rangeMin;
        return $this;
    }

    public function getRangeMax():int|float|null
    {
        return $this->rangeMax;
    }

    public function setRangeMax(int|float|null $rangeMax): Request
    {
        $this->rangeMax = $rangeMax;
        return $this;
    }

    public function getBitLength(): ?int
    {
        return $this->bitLength;
    }

    public function setBitLength(?int $bitLength): Request
    {
        $this->bitLength = $bitLength;
        return $this;
    }

}