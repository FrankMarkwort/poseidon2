<?php

namespace Nmea\Math\Vector;

use Nmea\Math\EnumRange;
use PHPUnit\Framework\TestCase;

class PolarVectorTest extends TestCase
{

    public function testRotateNeg()
    {
       $vector = (new PolarVector())->setR(10)->setOmega(deg2rad(-170));
       $this->assertEquals(10, $vector->getR());
       $this->assertEquals(-170, rad2deg($vector->getOmega(EnumRange::G180)));
       $this->assertEquals(190, rad2deg($vector->getOmega(EnumRange::G360)));
       $this->assertEqualsWithDelta(20, rad2deg($vector->rotate(deg2rad(-170))->getOmega(EnumRange::G360)),0.01);
       $this->assertEqualsWithDelta(20, rad2deg($vector->getOmega(EnumRange::G180)), 0.01);
    }
    public function testGetPositivOmega()
    {
        $vector = new PolarVector();
        $vector->setOmega(0);
        $this->assertEquals(0 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(0, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(pi()/2);
        $this->assertEquals(90 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(pi());
        $this->assertEquals(180 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(180, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(pi() * 3 / 2);
        $this->assertEquals(270 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(-90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(2 * pi());
        $this->assertEquals(0 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(0, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(3 * pi());
        $this->assertEquals(180 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(180, rad2deg($vector->getOmega(EnumRange::G180)));
    }

    public function testZeroR()
    {
        $vector = new PolarVector();
        $vector->setR(0);
        $vector->setOmega( deg2rad(0));
        $this->assertEquals(0 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(0, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega( deg2rad(90));
        $this->assertEquals(90 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega( deg2rad(180));
        $this->assertEquals(180 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(180, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega( deg2rad(270));
        $this->assertEquals(270 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(-90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega( deg2rad(255));
        $vector->setR(0.07);
        $this->assertEquals(255 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEqualsWithDelta(-105, rad2deg($vector->getOmega(EnumRange::G180)), 0.1);
    }

    public function testRotate()
    {
        $vector = new PolarVector();
        $vector->setOmega(deg2rad(90));
        $vector->setR(20);
        $this->assertEquals(90, rad2deg($vector->getOmega()));
        $this->assertEquals(20, $vector->getR());
        $vector->rotate(deg2rad(10));
        $this->assertEquals(100, rad2deg($vector->getOmega()));
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(80));
        $this->assertEquals(180, rad2deg($vector->getOmega()));
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(10));
        $this->assertEquals(190, rad2deg($vector->getOmega()));
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(80));
        $this->assertEquals(270, rad2deg($vector->getOmega()));
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(80));
        $this->assertEqualsWithDelta(350, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(11));
        $this->assertEqualsWithDelta(1, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);

        $vector->rotate(deg2rad(-2));
        $this->assertEqualsWithDelta(359, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-89));
        $this->assertEqualsWithDelta(270, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-89));
        $this->assertEqualsWithDelta(181, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-1));
        $this->assertEqualsWithDelta(180, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-45));
        $this->assertEqualsWithDelta(135, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-45));
        $this->assertEqualsWithDelta(90, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-45));
        $this->assertEqualsWithDelta(45, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);
        $vector->rotate(deg2rad(-45));
        $this->assertEqualsWithDelta(0, rad2deg($vector->getOmega()),0.0001);
        $this->assertEqualsWithDelta(20, $vector->getR(), 0.0001);

    }

     public function testRotateZeroR()
    {
        $vector = new PolarVector();
        $vector->setOmega(deg2rad(90));
        $vector->setR(0);
        $this->assertEquals(90, rad2deg($vector->getOmega()));
        $this->assertEquals(0, $vector->getR());
        $vector->rotate(deg2rad(90));
        $this->assertEquals(0, rad2deg($vector->getOmega()));
        $this->assertEquals(0, $vector->getR());
    }

     public function testGetNegativOmega()
    {
        $vector = new PolarVector();
        $vector->setOmega(0);
        $this->assertEquals(0 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(0, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(-pi()/2);
        $this->assertEquals(270 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(-90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(pi());
        $this->assertEquals(180 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(180, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(-pi() * 3 / 2);
        $this->assertEquals(90 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(90, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(-2 * pi());
        $this->assertEquals(0 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(0, rad2deg($vector->getOmega(EnumRange::G180)));

        $vector->setOmega(-3 * pi());
        $this->assertEquals(180 , rad2deg($vector->getOmega(EnumRange::G360)));
        $this->assertEquals(180, rad2deg($vector->getOmega(EnumRange::G180)));
    }
}
