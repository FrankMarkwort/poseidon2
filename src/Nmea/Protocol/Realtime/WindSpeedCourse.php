<?php

namespace Nmea\Protocol\Realtime;

use Nmea\Math\EnumRange;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Math\Vector\PolarVectorOperation;

class WindSpeedCourse
{
    private const MIN_SPEED_VOTE_AS_SOG = 0.5; //m/s
    private ?PolarVector $courseOverGround = null;
    private ?Rad $vesselHeading = null;
    private ?PolarVector $apparentWind = null;

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

    public function toArray():array
    {
        $twa = new Rad();
        if ($this->getCourseOverGround()->getR() > static::MIN_SPEED_VOTE_AS_SOG) {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getCourseOverGround()->getOmega());
        } else {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getVesselHeading()->getOmega());
        }
        return [
            'twd' => round($this->angleGrad($this->getTrueWind()->getOmega()), 0),
            'aws' => round($this->msToKnots($this->getApparentWind()->getR()),1),
            'awa' => round($this->angleGrad($this->getApparentWind()->getOmega(EnumRange::G180)),0),
            'tws' => round($this->msToKnots($this->getTrueWind()->getR()),1),
            'twa' => round($this->angleGrad($twa->getOmega(EnumRange::G180)),0),
            'cog' => round($this->angleGrad($this->getCourseOverGround()->getOmega()),0),
            'sog' => round($this->msToKnots($this->getCourseOverGround()->getR()),1),
            'vmg' => round($this->getVmg($twa->getOmega(EnumRange::G180)),1),
            'vesselHeading' => $this->angleGrad($this->getVesselHeading()->getOmega())
        ];
    }

    public function toJson():string
    {
        return json_encode($this->toArray());
    }

    private function getVmg($twa):float
    {
        return $this->msToKnots($this->getCourseOverGround()->getR()) * cos($twa);
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
}