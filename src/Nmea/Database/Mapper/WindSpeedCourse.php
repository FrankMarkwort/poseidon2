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
    private float $vesselHeading;

    public function __construct(private Database $database)
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

    private function getCogReference(): string
    {
        return $this->cogReference;
    }

    public function setCogReference(string $cogReference): WindSpeedCourse
    {
        $this->cogReference = $cogReference;
     
        return $this;
    }

    private function getWindRefernce(): string
    {
        return $this->windRefernce;
    }

    public function setWindRefernce(string $windRefernce): WindSpeedCourse
    {
        $this->windRefernce = $windRefernce;
     
        return $this;
    }

    private function getApparentWindAngle(): float
    {
        return $this->anglePi($this->windAngle);
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

    public function store()
    {
        $sqlformat = 'REPLACE INTO wind_speed_minute (`timestamp`, twd, aws, awa, tws, twa, cog, sog, vesselHeading)'
            . " VALUES ('%s', %s, %s, %s, %s, %s, %s, %s, %s)";

        $sql = sprintf($sqlformat,
            $this->getTime(),
            $this->angleGrad($this->getTrueWindDirection()),
            $this->speedKnots($this->getApparentWindSpeed()),
            $this->angleGrad($this->getApparentWindAngle()),
            $this->speedKnots($this->getApparentWindSpeed()),
            $this->angleGrad($this->getTrueWindAngle()),
            $this->angleGrad($this->getCog()),
            $this->speedKnots($this->getSog()),
            $this->angleGrad($this->getVesselHeading())
        );

        $this->database::getInstance()->execute($sql);

    }

    private function getVesselHeading(): float
    {
        return $this->vesselHeading;
    }

    private function getTrueWindDirection(): float
    {
        return (pi() - $this->getTrueWindAngle()) + $this->getVesselHeading();
    }

    public function setVesselHeading(float $vesselHeading): WindSpeedCourse
    {
        $this->vesselHeading = $vesselHeading;

        return $this;
    }

    private function  getTrueWindAngle():float
    {
         //TODO genzwerte
        # cos Ω = (a² + b² – c²) / (2ab)

        $a = $this->getSog();
        $b = $this->getApparentWindSpeed();
        $c = $this->getTrueWindSpeed();
        if (abs($a) <= 0.001 || abs($b) <= 0.001) {

            return $this->getApparentWindAngle();
        }

        return acos((pow($a, 2) + pow($b, 2) - pow($c, 2)) / (2 * $a * $b));

    }

    private function getTrueWindSpeed():float
    {
        #c² = a² + b² – 2ab * cos θ,
        $a = $this->getSog();
        $b = $this->getApparentWindSpeed();
        $O = $this->getApparentWindAngle();
        $z = pow($a, 2) + pow($b, 2) - (2 * $a * $b * cos($O));
        if ($z >= 0) {

            return sqrt($z);
        }

        return 0;
    }

    public function anglePi(float $angle):float
    {
         return $angle <= pi() ? $angle : (2 * pi() - $angle) * -1;
    }


    private function addRadAngle(float $angle1, float $angle2): float
    {
        return ($angle1 + $angle2) % (2 * pi());
    }

    private function angleGrad(float $angle): float
    {
        return round(rad2deg($angle),0);
    }

    private function speedKnots(float $speed):float
    {
        return round($speed * 1.94384 ,1);
    }
}