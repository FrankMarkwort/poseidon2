<?php
declare(strict_types=1);

namespace Nmea\Math;

abstract class AbstractAngleRad
{
    private const float DELTA = 0.0001;

    protected float $omega = 0;

    public function setOmega(float $omega): self
    {
        $this->omega = $omega;

        return $this;
    }

    public function getOmega(EnumRange $range = EnumRange::G360): float
    {
        return $this->getRadInRange($this->omega, $range);
    }

    protected function getRadInRange(float $rad, EnumRange $range = EnumRange::G360): float
    {
        if ($range === EnumRange::G180) {

            return  $this->getRad180($rad);
        }

        return $this->getRad360($rad);
    }
    protected function getRad360(float $rad):float
    {

        if ($this->isEquals2PiOrZero($rad)) {

            return 0;

        } elseif ($rad > 2 * pi()) {

            return fmod($rad, 2 * pi()) ;

        } elseif ($rad < 0) {

            return 2 * pi() + fmod($rad, 2 * pi()) ;
        }

        return $rad;
    }

    protected function getRad180(float $rad):float
    {
         if ($this->isEquals2PiOrZero($rad)) {

             return 0;

         } elseif ($this->isEqualsPi($rad)) {

             return pi();

         } elseif ($this->isBetween0AndPi($rad)) {

            return $rad;

         } elseif ($rad > 2 * pi()) {

             return fmod($rad, 2 * pi());

         } elseif ($this->isBetween0And2Pi($rad)) {

             return (2 * pi() - fmod($rad, 2 * pi())) * (-1);

         } elseif ($rad > -pi() && $rad < 0 ) {

             return fmod($rad, pi());

         } elseif ($rad < -2 * pi() && $rad < -pi() )  {
            if ($this->isEqualsPi(abs(fmod($rad, 2 * pi()))))
            {
                return abs(fmod($rad, 2 * pi()));

            } else {

                 return fmod($rad, 2 * pi());
            }
         } elseif ($rad < -pi() && $rad > - 2 * pi()) {

             return abs(fmod($rad, pi()));
         }

         return $rad;
    }

    protected function isBetween0And2Pi(float $rad):bool
    {
        return $rad > 0 && $rad < 2 * pi();
    }

    protected function isBetween0AndPi(float $rad):bool
    {
        return $rad > 0 && $rad < pi();
    }

    protected function isEqualsPi(float $rad): bool
    {
        return $this->isEqualsWithDelta(abs($rad), pi());
    }

    protected function isEquals2PiOrZero(float $rad): bool
    {
        return ($this->isEqualsWithDelta(abs($rad),2 * pi()) || $this->isEqualsWithDelta(abs($rad),0));
    }

    protected function isEqualsWithDelta(float $number1, float $number2 , float $delta = self::DELTA): bool
    {
        return abs($number1 - $number2) < $delta;
    }

    abstract public function rotate(float $rad):self;
}
