<?php

namespace TestsNmea\Database\Mapper;

use Nmea\Database\Mapper\WindSpeedCourse;
use PHPUnit\Framework\TestCase;
use Nmea\Database\Database;

class WindSpeedCourseTest extends TestCase
{
    private $mapper;
    protected function setUp(): void
    {
        $this->mapper = new WindSpeedCourse(Database::getInstance());
        $this->mapper->setCog(deg2rad(45));
        $this->mapper->setSog(10 / 1.944);
        $this->mapper->setApparentWindAngle(deg2rad(30));
        $this->mapper->setApparentWindSpeed(20 / 1.944);
        $this->mapper->setVesselHeading(deg2rad(45));
    }

    public function testTrueWind()
    {
        $this->assertEquals(12.4, round($this->mapper->getTrueWindSpeed() * 1.944,1));
        $this->assertEquals(126, round(rad2deg($this->mapper->getTrueWindAngle())),0);
        $this->assertEquals(99, round(rad2deg($this->mapper->getTrueWindDirection()),0));

        $this->mapper->setVesselHeading(deg2rad(45));
        $this->mapper->setSog(0);

        $this->assertEquals(20, round($this->mapper->getTrueWindSpeed() * 1.944,1));
        $this->assertEquals(30, round(rad2deg($this->mapper->getTrueWindAngle())),0);
        $this->assertEquals(195, round(rad2deg($this->mapper->getTrueWindDirection()),0));
    }
}
