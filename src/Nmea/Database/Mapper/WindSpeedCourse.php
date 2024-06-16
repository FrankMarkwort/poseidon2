<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\DatabaseInterface;
use Nmea\Math\EnumRange;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Math\Vector\PolarVectorOperation;

class WindSpeedCourse
{

    private const MIN_SPEED_VOTE_AS_SOG = 0.5; //m/s
    private string $time;
    private ?PolarVector $courseOverGround = null;
    private ?Rad $vesselHeading = null;
    private ?PolarVector $apparentWind = null;
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

    public function setCourseOverGround(PolarVector $boatMoveTo):self
    {
        $this->courseOverGround = $boatMoveTo;

        return $this;
    }

    private function getCourseOverGround():PolarVector
    {
        return $this->courseOverGround;
    }

    public function setVesselHeading(Rad $vesselHeading):self
    {
        $this->vesselHeading = $vesselHeading;

        return $this;
    }

    private function getVesselHeading():Rad
    {
        return $this->vesselHeading;
    }

    public function setApparentWind(PolarVector $apparentWind): WindSpeedCourse
    {
        $this->apparentWind = $apparentWind;

        return $this;
    }

    private function getApparentWind():PolarVector
    {
        return $this->apparentWind;
    }

    public function getWaterTemperature(): float
    {
        return $this->waterTemperature;
    }

    public function setWaterTemperature(float $waterTemperature): WindSpeedCourse
    {
        $this->waterTemperature = $waterTemperature;

        return $this;
    }

    /**
     * TODO move too mapper
     */
    public function store():void
    {
        $sqlformat = 'REPLACE INTO wind_speed_minute (`timestamp`, twd, aws, awa, tws, twa, cog, sog, vesselHeading, waterTemperature )'
            . " VALUES ('%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)";
        $sql = vsprintf($sqlformat, $this->getStoreArray());
        $this->database::getInstance()->execute($sql);

    }

    public function getStoreArray():array
    {
        $twa = new Rad();
        if ($this->getCourseOverGround()->getR() > static::MIN_SPEED_VOTE_AS_SOG) {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getCourseOverGround()->getOmega());
        } else {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getVesselHeading()->getOmega());
        }
        return [
            'timestamp' => $this->getTime(),
            'twd' => $this->angleGrad($this->getTrueWind()->getOmega()),
            'aws' => $this->msToKnots($this->getApparentWind()->getR()),
            'awa' => $this->angleGrad($this->getApparentWind()->getOmega(EnumRange::G180)),
            'tws' => $this->msToKnots($this->getTrueWind()->getR()),
            'twa' => $this->angleGrad($twa->getOmega(EnumRange::G180)),
            'cog' => $this->angleGrad($this->getCourseOverGround()->getOmega()),
            'sog' => $this->msToKnots($this->getCourseOverGround()->getR()),
            'vesselHeading' => $this->angleGrad($this->getVesselHeading()->getOmega()),
            'waterTemperature' => $this->kelvinToCelsius($this->getWaterTemperature())
        ];
    }

    private function getTrueWind(): PolarVector
    {
        $courseOverGround = $this->getCourseOverGround();
        if ($courseOverGround->getR() > static::MIN_SPEED_VOTE_AS_SOG) {
            $headingOrCog = $courseOverGround->getOmega();
        } else {
            $headingOrCog = $this->getVesselHeading()->getOmega();
        }

        $course =  (new PolarVector())->setR($courseOverGround->getR())->setOmega($headingOrCog);
        $speedWind =  $courseOverGround->againstVector(true);
        $apparentWind2 = $this->getApparentWind()->rotate($course->getOmega(), true);

        return  (new PolarVectorOperation())($speedWind, $apparentWind2);
    }

    private function angleGrad(float $angle): float
    {
        return round(rad2deg($angle), 0);
    }

    private function msToKnots(float $speed):float
    {
        return round($speed * 1.94384 ,1);
    }

    private function kelvinToCelsius(float $kelvin):float
    {
        return round($kelvin - 273.15, 1);
    }
}