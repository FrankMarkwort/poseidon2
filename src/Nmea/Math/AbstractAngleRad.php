<?php

namespace Nmea\Math;

abstract class AbstractAngleRad
{
    protected function getRadAngle(float $rad, EnumRange $range = EnumRange::G360): float
    {
        if ($range === EnumRange::G180) {

            return  $this->getRad180($rad);
        }

        return $this->getRad360($rad);
    }
    protected function getRad360(float $rad):float
    {
        if (abs($rad) === 2 * pi() || abs($rad) === 0.0) {

            return 0;

        } elseif ($rad > 0 && $rad < 2 * pi()) {

            return $rad;

        } elseif ($rad > 2 * pi()) {

            return fmod($rad, 2 * pi()) ;

        } elseif ($rad < 0) {

            return 2 * pi() + fmod($rad, 2 * pi()) ;
        }

        return $rad;
    }

    protected function getRad180(float $rad):float
    {
         if (abs($rad) === 2 * pi() || abs($rad) === 0.0) {

             return 0;

         } elseif (abs($rad) === pi()) {

             return pi();

         } elseif ($rad > 0 && $rad < pi()) {

            return $rad;

         } elseif ($rad > 0 && $rad > 2 * pi()) {

             return fmod($rad, 2 * pi());

         } elseif ($rad > 0 && $rad < 2 * pi()) {

             return (2 * pi() - fmod($rad, 2 * pi())) * (-1);

         } elseif ($rad > -pi() && $rad < 0  ) {

             return fmod($rad, pi());

         } elseif ($rad < -2* pi() && $rad < -pi())  {

            return fmod($rad, pi());

         }

         return $rad;
    }
}
