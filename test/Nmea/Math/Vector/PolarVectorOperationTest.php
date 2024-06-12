<?php

namespace Nmea\Math\Vector;

use Nmea\Math\EnumRange;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PolarVectorOperationTest extends TestCase
{


    /**
     * helper https://planetcalc.com/8066/
     */
    public static function dataProvider()
    {
        return [
           'P0' => [10 ,45, 10,  45 , 20.00   , 45.0,   EnumRange::G180, Operator::PLUS],
           'P1' => [10 ,45, 10,  90 , 18.48   , 67.5,   EnumRange::G180, Operator::PLUS],
           'P2' =>  [10 ,45, 10, 180 , 7.65    , 112.50, EnumRange::G180, Operator::PLUS],
           'P3' => [10 ,45, 10, 220 , 0.87    , 132.50, EnumRange::G180, Operator::PLUS],
           'P4' =>  [10 ,170, 10, 220 , 18.13  , -165.00,EnumRange::G180, Operator::PLUS],
           'P5' =>  [10 ,170, 10, 220 , 18.13  , 195.00, EnumRange::G360, Operator::PLUS],
           'P6' => [10 ,190, 10, 220 , 19.32  , -155,   EnumRange::G180, Operator::PLUS],
           'P7' => [10 ,190, 10, 220 , 19.32  , 205,    EnumRange::G360, Operator::PLUS],

           'M0' => [10 , 45, 10,  45 , 0   ,  0 , EnumRange::G360, Operator::MINUS],
           'M1' => [10 , 90, 20,  90 , 10  , -90 , EnumRange::G180, Operator::MINUS],
           'M2' => [10 , 90, 20,  180 , 22.36  , 26.57 , EnumRange::G180, Operator::MINUS],
           'M3' => [10 , 50, 10,  -170 , 18.79, 30.00, EnumRange::G180, Operator::MINUS],
           'M4' => [10 , 50, 10,  -170 , 18.79, 30.00, EnumRange::G360, Operator::MINUS],
           'M5' => [10 , 50, 10,  220, 19.92, 45, EnumRange::G360, Operator::MINUS],
           'M6' => [10 , 50, 10,  220, 19.92, 45, EnumRange::G360, Operator::MINUS],
        ];

         # [ 10.00 , 50 , 10.00 , -170, 19.9, 175, 225],
    }

    #[DataProvider('dataProvider')]
    public function testAdd(float $r1, float $o1, float $r2, float $o2, float $r, float $o, EnumRange $range= EnumRange::G360,Operator $operator= Operator::PLUS)
    {
        $addierer = new PolarVectorOperation();
        $vector1 = (new PolarVector())->setR($r1)->setOmega(deg2rad($o1));
        $vector2 = (new PolarVector())->setR($r2)->setOmega(deg2rad($o2));
        $vector3 = $addierer($vector1, $vector2, $operator);
        $this->assertEqualsWithDelta($r, $vector3->getR(), 0.1);
        $this->assertEqualsWithDelta($o, rad2deg($vector3->getOmega($range)), 0.01);
    }
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
        $this->assertEqualsWithDelta(135, rad2deg($vector5->getOmega()), 0.01);
        $this->assertEquals(14.142135623730951, $vector5->getR());

        $vector6 = (new PolarVector())->setOmega(deg2rad(270))->setR(20);
        $vector7 = $addierer($vector5, $vector6);

        $this->assertEquals(225, round(rad2deg($vector7->getOmega()),0));
        $this->assertEqualsWithDelta(14.142135623730951, $vector7->getR(),0.01 );

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

    private function kn2ms(float $kn):float
    {
        return $kn / 1.94384;
    }
}
