<?php

namespace Nmea\Database\Entity;

class Positions implements ComparableInterface
{
    private float $id;
    private float $fidWindSpeedHour;
    private string $timestamp;
    private float $latitude;
    private float $longitude;

    public function getId(): float
    {
        return $this->id;
    }

    public function setId(float $id): Positions
    {
        $this->id = $id;

        return $this;
    }

    public function getFidWindSpeedHour(): float
    {
        return $this->fidWindSpeedHour;
    }

    public function setFidWindSpeedHour(float $fidWindSpeedHour): Positions
    {
        $this->fidWindSpeedHour = $fidWindSpeedHour;

        return $this;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): Positions
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): Positions
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): Positions
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function compareTo(ComparableInterface $position):bool
    {
        if ($position instanceof Positions) {
            if (round($this->getLatitude(), 3) === round($position->getLatitude(), 3)
                && round($this->getLongitude(), 3) === round($position->getLongitude(), 3)) {
                return true;
            }
        }
        return false;
    }
}