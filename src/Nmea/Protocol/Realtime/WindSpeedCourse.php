<?php

namespace Nmea\Protocol\Realtime;

use Nmea\Math\EnumRange;
use Nmea\Math\Skalar\Rad;

class WindSpeedCourse extends AbstractWindSpeedCourse
{
    public function toArray():array
    {
        $twa = new Rad();
        if ($this->getCourseOverGround()->getR() > static::MIN_SPEED_IN_MS_VOTE_AS_SOG) {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getCourseOverGround()->getOmega());
        } else {
            $twa->setOmega($this->getTrueWind()->getOmega() - $this->getVesselHeading()->getOmega());
        }
        return [
            'twd' => round($this->angleGrad($this->getTrueWind()->getOmega())),
            'aws' => round($this->msToKnots($this->getApparentWind()->getR()),1),
            'awa' => round($this->angleGrad($this->getApparentWind()->getOmega(EnumRange::G180))),
            'tws' => round($this->msToKnots($this->getTrueWind()->getR()),1),
            'twa' => round($this->angleGrad($twa->getOmega(EnumRange::G180))),
            'cog' => round($this->angleGrad($this->getCourseOverGround()->getOmega())),
            'sog' => round($this->msToKnots($this->getCourseOverGround()->getR()),1),
            'vmg' => round($this->getVmg($twa->getOmega(EnumRange::G180)),1),
            'vesselHeading' => $this->angleGrad($this->getVesselHeading()->getOmega())
        ];
    }

    private function getVmg($twa):float
    {
        return $this->msToKnots($this->getCourseOverGround()->getR()) * cos($twa);
    }
}