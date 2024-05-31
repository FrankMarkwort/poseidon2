<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\Database;

class WindSpeedCourse
{
    private int $idMinute;
    private string $time;
    private float $windSpeed;
    private float $windAngle;
    private string $windRefernce;
    private string $cogReference;
    private float $cog;
    private float $sog;

    public function __construct(private Database $database)
    {

    }
    public function getIdMinute(): int
    {
        return $this->idMinute;
    }

    public function setIdMinute(int $idMinute): WindSpeedCourse
    {
        $this->idMinute = $idMinute;

        return $this;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): WindSpeedCourse
    {
        $this->time = $time;
     
        return $this;
    }

    public function getSog(): float
    {
        return $this->sog;
    }

    public function setSog(float $sog): WindSpeedCourse
    {
        $this->sog = $sog;
     
        return $this;
    }

    public function getCog(): float
    {
        return $this->cog;
    }

    public function setCog(float $cog): WindSpeedCourse
    {
        $this->cog = $cog;
     
        return $this;
    }

    public function getCogReference(): string
    {
        return $this->cogReference;
    }

    public function setCogReference(string $cogReference): WindSpeedCourse
    {
        $this->cogReference = $cogReference;
     
        return $this;
    }

    public function getWindRefernce(): string
    {
        return $this->windRefernce;
    }

    public function setWindRefernce(string $windRefernce): WindSpeedCourse
    {
        $this->windRefernce = $windRefernce;
     
        return $this;
    }

    public function getWindAngle(): float
    {
        return $this->windAngle;
    }

    public function setWindAngle(float $windAngle): WindSpeedCourse
    {
        $this->windAngle = $windAngle;
     
        return $this;
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(float $windSpeed): WindSpeedCourse
    {
        $this->windSpeed = $windSpeed;
     
        return $this;
    }

    public function store()
    {
        $sqlformat = 'REPLACE INTO wind_speed_minute (`timestamp`, windSpeed, windAngle, windRefernce, COGReference, COG, SOG)'
            . " VALUES ('%s', %s, %s, '%s', '%s', %s, %s)";

        $sql = sprintf($sqlformat,
            $this->getTime(),
            $this->getWindSpeed(),
            $this->getWindAngle(),
            $this->getWindRefernce(),
            $this->getCogReference(),
            $this->getCog(),
            $this->getSog()
        );

        $this->database::getInstance()->execute($sql);

        #var_dump($sql);
    }
}