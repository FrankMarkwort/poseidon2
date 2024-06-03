<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\DatabaseInterface;

class WindSpeedCourse
{
    private string $time;
    private float $windSpeed;
    private float $windAngle;
    private float $cog;
    private float $sog;
    private float $vesselHeading;
    private float $waterTemperature;

    public function __construct(private DatabaseInterface $database)
    {
    }

    private function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): WindSpeedCourse
    {
        $this->time = $time;
     
        return $this;
    }

    private function getSog(): float
    {
        return $this->sog;
    }

    public function setSog(float $sog): WindSpeedCourse
    {
        $this->sog = $sog;
     
        return $this;
    }

    private function getCog(): float
    {
        return $this->cog;
    }

    public function setCog(float $cog): WindSpeedCourse
    {
        $this->cog = $cog;
     
        return $this;
    }

    private function getApparentWindAngle(): float
    {
        return $this->angleMaximalPi($this->windAngle);
    }

    public function setApparentWindAngle(float $windAngle): WindSpeedCourse
    {
        $this->windAngle = $windAngle;
     
        return $this;
    }

    private function getApparentWindSpeed(): float
    {
        return $this->windSpeed;
    }

    public function setApparentWindSpeed(float $windSpeed): WindSpeedCourse
    {
        $this->windSpeed = $windSpeed;
     
        return $this;
    }

    private function getWaterTemperature(): float
    {
        return $this->waterTemperature;
    }

    public function setWaterTemperature(float $waterTemperature): WindSpeedCourse
    {
        $this->waterTemperature = $waterTemperature;

        return $this;
    }



    public function store()
    {
        $sqlformat = 'REPLACE INTO wind_speed_minute (`timestamp`, twd, aws, awa, tws, twa, cog, sog, vesselHeading, waterTemperature )'
            . " VALUES ('%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)";

        $sql = sprintf($sqlformat,
            $this->getTime(),
            $this->angleGrad($this->getTrueWindDirection()),
            $this->msToKnots($this->getApparentWindSpeed()),
            $this->angleGrad($this->getApparentWindAngle()),
            $this->msToKnots($this->getApparentWindSpeed()),
            $this->angleGrad($this->getTrueWindAngle()),
            $this->angleGrad($this->getCog()),
            $this->msToKnots($this->getSog()),
            $this->angleGrad($this->getVesselHeading()),
            $this->kelvinToCelsius($this->getWaterTemperature())
        );

        $this->database::getInstance()->execute($sql);

    }

    private function getVesselHeading(): float
    {
        return $this->vesselHeading;
    }

    public function getTrueWindDirection(): float
    {
        if ($this->getSog() >= 1) {

            return ($this->getTrueWindAngle()) + $this->getCog() % (2 * pi());
        }

        return ($this->getTrueWindAngle()) + $this->getVesselHeading() % (2 * pi());
    }

    public function setVesselHeading(float $vesselHeading): WindSpeedCourse
    {
        $this->vesselHeading = $vesselHeading;

        return $this;
    }

    public function getTrueWindAngle():float
    {
        # cos Ω = (a² + b² – c²) / (2ab)
        $a = $this->getTrueWindSpeed();
        $b = $this->getSog();
        $c = $this->getApparentWindSpeed();
        if (abs(2 * $a * $b) <= 0.001) {

            return $this->getApparentWindAngle();
        }

        return acos((pow($a, 2) + pow($b, 2) - pow($c, 2)) / (2 * $a * $b));

    }

    public function getTrueWindSpeed():float
    {
        #c² = a² + b² – 2ab * cos θ,
        $a = $this->getSog();
        $b = $this->getApparentWindSpeed();
        $O = $this->getApparentWindAngle();
        $z = pow($a, 2) + pow($b, 2) - (2 * $a * $b * cos($O));
        if ($z >= 0) {

            return sqrt($z);
        }

        return $this->getApparentWindSpeed();
    }

    public function angleMaximalPi(float $angle):float
    {
         return $angle <= pi() ? $angle : (2 * pi() - $angle) * -1;
    }

    private function angleGrad(float $angle): float
    {
        return round(rad2deg($angle),0);
    }

    private function msToKnots(float $speed):float
    {
        return round($speed * 1.94384 ,1);
    }

    private function kelvinToCelsius(float $kelvin):float
    {
        return $kelvin - 273.15;

    }
}