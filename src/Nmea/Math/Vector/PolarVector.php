<?php

namespace Nmea\Math\Vector;

class PolarVector
{
    private float $r;
    private float $omega;

    public function getR(): float
    {
        return $this->r;
    }

    public function setR(float $r): PolarVector
    {
        $this->r = $r;

        return $this;
    }

    public function rotate(float $angle): PolarVector
    {
        //        (x´,y´) = (r × cos( omega )·cosθ – r × sin( omega )·sin θ,   r × cos( Omega )·sinθ + r × sin( Omega )·cosθ)
        $r = $this->r;
        $omega = $this->omega;
        $x = $r * cos( $omega ) * cos($angle) - $r * sin( $omega ) * sin($angle);
        $y = $r *  cos( $omega ) * sin($angle) + $r * sin($omega) * cos($angle);
        $newOmega = atan2($y, $x);
        #if ($newOmega < 0) {
        #    $newOmega = $newOmega + 2 * pi();
        #}
        if ($x < 0 && $y < 0) {
            $newOmega = 2 * pi() + $newOmega;
        } elseif ($x > 0 && $y < 0) {
            $newOmega = 2 * pi() + $newOmega;
        }
        $newR = sqrt(pow($x,2) + pow($y,2));
        $this->setR($newR);
        $this->setOmega($newOmega);

        return $this;
    }

    public function getOmega(Range $range = Range::G360): float
    {
        $omega = $this->omega;
        $omega = $this->rangePi($omega, $range);

        return $omega;
    }

    public function setOmega(float $omega): PolarVector
    {
        $this->omega = $omega;

        return $this;
    }

    public function getVector(Range $range = Range::G360):array
    {
        return [
            $this->getR(),
            $this->getOmega($range)
        ];
    }

    private function rangePi(float $rad, Range $range):float
    {
        if ($range === Range::G180) {

            return ($rad > pi() ? (2 * pi() - $rad) * -1 : $rad);

        }
        if ($rad < 0)  {

             return ($rad <= 2 * pi()) ? $rad % 2 * pi() : $rad;

        } elseif (round($rad,6) === round(2 * pi(), 6)) {

            return 0;

        } else {

            return ($rad >= 2 * pi()) ? $rad % 2 * pi() : $rad;
        }
    }
}