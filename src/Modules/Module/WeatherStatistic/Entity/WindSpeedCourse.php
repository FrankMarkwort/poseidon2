<?php
declare(strict_types=1);

namespace Modules\Module\WeatherStatistic\Entity;

//TODO remove from Module
use Nmea\Database\DatabaseInterface;
use Nmea\Math\EnumRange;
use Nmea\Math\Skalar\Rad;

class WindSpeedCourse extends AbstractWindSpeedCourse
{
    private string $time;
    private float $waterTemperature;

    private function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): WindSpeedCourse
    {
        $this->time = $time;
     
        return $this;
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

    public function toArray():array
    {
        $twa = new Rad();
        if ($this->getCourseOverGround()->getR() > static::MIN_SPEED_IN_MS_VOTE_AS_SOG) {
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

    private function kelvinToCelsius(float $kelvin):float
    {
        return round($kelvin - 273.15, 1);
    }

    public function toJson():string
    {
        return json_encode($this->toArray());
    }
}