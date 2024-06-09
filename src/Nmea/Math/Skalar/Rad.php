<?php

namespace Nmea\Math\Skalar;

use Nmea\Math\AbstractAngleRad;
use Nmea\Math\EnumRange;

class Rad extends AbstractAngleRad
{
    private float $rad;

    public function getRad(EnumRange $range = EnumRange::G360): float
    {
        return $this->getRadAngle($this->rad, $range);
    }


    public function getDeg(EnumRange $range = EnumRange::G360): float
    {
        return rad2deg($this->getRad($range));
    }

    public function setRad(float $rad): Rad
    {
        $this->rad = $rad;

        return $this;
    }


}