<?php

namespace Nmea\Math\Vector;

use Nmea\Math\AbstractAngleRad;
use Nmea\Math\EnumRange;

class PolarVector extends AbstractAngleRad
{
    private float $r;

    public function getR(): float
    {
        return $this->r;
    }

    public function setR(float $r): PolarVector
    {
        $this->r = $r;

        return $this;
    }

    public function againstVector( bool $clone = false):PolarVector
    {
        return $this->rotate(pi(), $clone);
    }

    public function rotate(float $rad , bool $clone = false): PolarVector
    {
        $r = $this->getR();
        $omega = $this->getOmega();
        $x = $r * cos( $omega ) * cos($rad) - $r * sin( $omega ) * sin($rad);
        $y = $r *  cos( $omega ) * sin($rad) + $r * sin($omega) * cos($rad);
        $newOmega = atan2($y, $x);
        if ($x < 0 && $y < 0) {
            $newOmega = 2 * pi() + $newOmega;
        } elseif ($x > 0 && $y < 0) {
            $newOmega = 2 * pi() + $newOmega;
        }
        $newR = sqrt(pow($x,2) + pow($y,2));
        if ($clone) {
            $newVector = clone $this;
            $newVector->setR($newR);
            $newVector->setOmega($newOmega);

            return $newVector;
        }

        $this->setR($newR);
        $this->setOmega($newOmega);

        return $this;
    }

    public function setOmega(float $omega): PolarVector
    {
        parent::setOmega($omega);

        return $this;
    }

    public function getVector(EnumRange $range = EnumRange::G360):array
    {
        return [
            $this->getR(),
            $this->getOmega($range)
        ];
    }

    /**
     * @deprecated
     */
    private function rangePi(float $rad, EnumRange $range):float
    {
        if ($range === EnumRange::G180) {

            return ($rad > pi() ? (2 * pi() - $rad) * -1 : $rad);

        }
        if ($rad < 0)  {

             return ($rad <= 2 * pi()) ? fmod($rad, 2 * pi()) : $rad;

        } elseif (round($rad,6) === round(2 * pi(), 6)) {

            return 0;

        } else {

            return ($rad >= 2 * pi()) ? fmod($rad, 2 * pi()) : $rad;
        }
    }
}