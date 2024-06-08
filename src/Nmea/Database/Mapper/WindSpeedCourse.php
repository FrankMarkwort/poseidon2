<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\DatabaseInterface;
use Nmea\Math\Vector\Operator;
use Nmea\Math\Vector\PolarVector;
use Nmea\Math\Vector\PolarVectorOperation;
use Nmea\Math\Vector\Range;

class WindSpeedCourse
{

    private const MIN_SPEED_VOTE_AS_SOG = 0.5; //m/s
    private string $time;
    private ?PolarVector $courseOverGround = null;
    private ?PolarVector $vesselHeading = null;
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

    public function setVesselHeading(PolarVector $vesselHeading):self
    {
        $this->vesselHeading = $vesselHeading;

        return $this;
    }

    private function getVesselHeading():PolarVector
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
     * move too mapper
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
        return [
            $this->getTime(),
            $this->angleGrad($this->getTrueWind()->getOmega()),
            $this->msToKnots($this->getApparentWind()->getR()),
            $this->angleGrad($this->getApparentWind()->getOmega(Range::G180)),
            $this->msToKnots($this->getTrueWind()->getR()),
            $this->angleGrad($this->getTrueWind()->getOmega(Range::G180)),
            $this->angleGrad($this->getCourseOverGround()->getOmega()),
            $this->msToKnots($this->getCourseOverGround()->getR()),
            $this->angleGrad($this->getVesselHeading()->getOmega()),
            $this->kelvinToCelsius($this->getWaterTemperature())
        ];
    }

    private function getTrueWind(): PolarVector
    {
        if ($this->getCourseOverGround()->getR() > static::MIN_SPEED_VOTE_AS_SOG) {

            return (new PolarVectorOperation())($this->getCourseOverGround() , $this->getApparentWind(), Operator::MINUS);
        }

        $trueWindVector = clone $this->getApparentWind();
        $trueWindVector->rotate( $this->getVesselHeading()->getOmega());

        return $trueWindVector;
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