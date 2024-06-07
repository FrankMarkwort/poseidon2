<?php

namespace Nmea\Database\Mapper\Vector;

use PHPUnit\Framework\TestCase;

class PolarVectorOperationTest extends TestCase
{

    public function test__invoke()
    {
        $addierer = new PolarVectorOperation();
        $vector1 = (new PolarVector())->setR(10)->setOmega(deg2rad(0));
        $vector2 = (new PolarVector())->setR(10)->setOmega(deg2rad(90));

        $vector3  = $addierer($vector1, $vector2);
        $this->assertEquals([14.142135623730951 ,0.7853981633974483], $vector3->getVector());
        $this->assertEquals(45, rad2deg($vector3->getOmega()));
        $this->assertEquals(14.142135623730951, $vector3->getR());

        $vector4 = (new PolarVector())->setOmega(deg2rad(180))->setR(20);
        $vector5 = $addierer($vector3, $vector4);
        $this->assertEquals(135, rad2deg($vector5->getOmega()));
        $this->assertEquals(14.142135623730951, $vector5->getR());

        $vector6 = (new PolarVector())->setOmega(deg2rad(270))->setR(20);
        $vector7 = $addierer($vector5, $vector6);

        $this->assertEquals(225, round(rad2deg($vector7->getOmega()),0));
        $this->assertEquals(14.142135623730951, $vector7->getR());

        $vector8 = (new PolarVector())->setOmega(deg2rad(0))->setR(20);
        $vector9 = $addierer($vector7, $vector8);

        $this->assertEquals(315, round(rad2deg($vector9->getOmega()),0));
        $this->assertEqualsWithDelta(14.142135623730951, $vector9->getR(), 0.00000000001);

        $vector10 = (new PolarVector())->setOmega(deg2rad(90))->setR(20);
        $vector11 = $addierer($vector9, $vector10);

        $this->assertEquals(45, round(rad2deg($vector11->getOmega()),0));
        $this->assertEqualsWithDelta(14.142135623730951, $vector11->getR(), 0.00000000001);



        #$r = $vector9->getR();
        #$o = rad2deg($vector9->getOmega()) ;
        #echo "[$r, $o]\n";

    }

    public function testMinus()
    {
        $addierer = new PolarVectorOperation();
        $vector1 = (new PolarVector())->setR(10)->setOmega(deg2rad(90));
        $vector2 = (new PolarVector())->setR(10)->setOmega(deg2rad(90));
        $vector3 = (new PolarVector())->setR(9)->setOmega(deg2rad(90));
        $vector50  = $addierer($vector1, $vector2,Operator::MINUS);
        $this->assertEquals(0, round(rad2deg($vector50->getOmega()),0));
        $this->assertEquals(0, $vector50->getR());
        $vector50  = $addierer($vector1, $vector3,Operator::MINUS);
        $this->assertEquals(90, round(rad2deg($vector50->getOmega()),0));
        $this->assertEquals(1, $vector50->getR());
    }

    public function testReal()
    {
        $addierer = new PolarVectorOperation();
         $aw = (new PolarVector())->setR(0)->setOmega(deg2rad(71));
         $course = (new PolarVector())->setR($this->kn2ms(3.1))->setOmega(deg2rad(-61));
         $tw  = $addierer($aw, $aw,Operator::MINUS);
         $this->assertEquals(0, round(rad2deg($tw->getOmega()),0));
         $this->assertEquals(0, $tw->getR());
         // I need a rotation
        // (x´,y´) = (x·cosθ – y·sinθ, x·sinθ + y·cosθ)
        // (x´,y´) = (r × cos( omega )·cosθ – r × sin( omega )·sin θ,   r × cos( Omega )·sinθ + r × sin( Omega )·cosθ)
        //x = r × cos( θ )
        //y = r × sin( θ )




    }

    private function rotate(PolarVector $vector,$winkel):PolarVector
    {

    }
    private function kn2ms(float $kn):float
    {
        return $kn / 1.94384;
    }
}
