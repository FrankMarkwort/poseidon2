<?php

namespace Nmea\Math\Skalar;

use Nmea\Math\AbstractAngleRad;
use Nmea\Math\EnumRange;

class Rad extends AbstractAngleRad
{
   public function getDegOmega(EnumRange $range = EnumRange::G360): float
    {
        return rad2deg($this->getOmega($range));
    }

    /**
     * @throws \Exception
     */
    public function rotate(float $rad): Rad
    {
        throw new \Exception('not implemented');
    }
}
