<?php
declare(strict_types=1);

namespace Modules\Module\Cron\WeatherStatistic\Entity;

//TODO remove from Module
use Core\Math\Skalar\Rad;
use Core\Math\Vector\PolarVector;
use Core\Math\Vector\PolarVectorOperation;

abstract class AbstractWindSpeedCourse
{
    protected const float MIN_SPEED_IN_MS_VOTE_AS_SOG = 0.5;
    protected ?PolarVector $courseOverGround = null;
    protected ?Rad $vesselHeading = null;
    protected ?PolarVector $apparentWind = null;

    protected function getTrueWind(): PolarVector
    {
        $courseOverGround = $this->getCourseOverGround();
        if ($courseOverGround->getR() > static::MIN_SPEED_IN_MS_VOTE_AS_SOG) {
            $headingOrCog = $courseOverGround->getOmega();
        } else {
            $headingOrCog = $this->getVesselHeading()->getOmega();
        }

        $course =  (new PolarVector())->setR($courseOverGround->getR())->setOmega($headingOrCog);
        $speedWind =  $courseOverGround->againstVector(true);
        $apparentWind2 = $this->getApparentWind()->rotate($course->getOmega(), true);

        return  (new PolarVectorOperation())($speedWind, $apparentWind2);
    }

    protected function getCourseOverGround():PolarVector
    {
        return $this->courseOverGround;
    }
    protected function getVesselHeading():Rad
    {
        return $this->vesselHeading;
    }

    public function setCourseOverGround(PolarVector $boatMoveTo):self
    {
        $this->courseOverGround = $boatMoveTo;

        return $this;
    }

    public function setVesselHeading(Rad $vesselHeading):self
    {
        $this->vesselHeading = $vesselHeading;

        return $this;
    }

    public function setApparentWind(PolarVector $apparentWind): self
    {
        $this->apparentWind = $apparentWind;

        return $this;
    }

    protected function getApparentWind():PolarVector
    {
        return $this->apparentWind;
    }

    protected function angleGrad(float $angle): float
    {
        return round(rad2deg($angle));
    }

    protected function msToKnots(float $speed):float
    {
        return round($speed * 1.94384 ,1);
    }

    abstract public function toArray():array;
}