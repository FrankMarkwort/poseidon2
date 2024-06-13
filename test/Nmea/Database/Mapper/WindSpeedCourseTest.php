<?php

namespace TestsNmea\Database\Mapper;

use Nmea\Database\Database;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Math\EnumRange;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\Operator;
use Nmea\Math\Vector\PolarVector;
use Nmea\Math\Vector\PolarVectorOperation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WindSpeedCourseTest extends TestCase
{
    private $mapper;
    protected function setUp(): void
    {
        $this->mapper = new WindSpeedCourse(Database::getInstance());
    }

    public static function dataProviderCsv()
    {
        $file = file_get_contents(__DIR__ . "/windspeedcoursetest.csv","r");
        foreach ( explode("\n", $file, -1) as $key => $line ) {
            if ($key == 0) continue;

            $stringData  = explode(',', $line);
            $new = [];
            foreach ($stringData as $value) {
                $new[] = floatval($value);
            }
            $data[] = $new;
        }
        return $data;
    }

    public static function dataprovider()
    {
        return [
            [ 10.00 , 45.00 , 45.00 , 10.00 , 45.00, 7.7, 113.00, 158],
            [ 10.00 , 50 , 50.00 , 10.00 , 45.00, 7.7, 113.00, 163],
            [ 10.00 , 50 , 50 , 10.00 , 170, 19.9, 175, 225],
            [ 10.00 , 50  , 50 , 10.00 , 180, 20, 180, 230],
            [ 10.00 , 170  , 170 , 10.00 , 92, 14.4, 136, 306],
            [ 10.00 , 50  , 50 , 10.00 , 170, 19.9, 175, 225],
            [ 10.00 , 50 , 50 , 10.00 , -170, 19.9, 185, 235],
            [ 10.00 , 190, 190, 10.00, 90.0, 14.1, 135, 325]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFormula(float $sog, float $cog, float $heading, float $aws, float $awa,float $expectedTws,float $expectedTwa,float $expectedTwd)
    {
        #$tws = sqrt( pow($sog,2) + pow($aws,2) - (2 * $aws * $sog * cos(deg2rad($awa))));
        #$beta = acos((pow($aws,2) -  pow($tws,2) - pow($sog,2)) / ( 2 * $tws * $sog));

        #$twd = $beta + deg2rad($heading);


        #$this->assertEqualsWithDelta( $expectedTws , $tws, 0.1,'tws');
        #$this->assertEquals( $expectedTwd , $this->rad2deg($twd) ,'twd');
        #$this->assertEquals( $expectedTwa , ($this->rad2deg($twd - $heading)),'twa');


        $course =  (new PolarVector())->setR($sog)->setOmega(deg2rad($heading));
        $speedWind =  $course->againstVector(true);
        $apparentWind = (new PolarVector())->setR($aws)->setOmega(deg2rad($awa));
        $apparentWind2 = $apparentWind->rotate($course->getOmega(EnumRange::G360), true);
        $trueWind = (new PolarVectorOperation())($speedWind, $apparentWind2);


        $this->assertEqualsWithDelta( $expectedTws, $trueWind->getR(), 0.9,'tws');
        $this->assertEqualsWithDelta( $expectedTwd , rad2deg($trueWind->getOmega()) ,0.9,'tws');
        $this->assertEqualsWithDelta( $expectedTwa , rad2deg($trueWind->getOmega() - $course->getOmega()) ,0.9,'twa');

        $this->mapper->setCourseOverGround((new PolarVector())->setR($sog)->setOmega(deg2rad($cog)));
        $this->mapper->setVesselHeading((new Rad())->setOmega(deg2rad($heading)));
        $this->mapper->setApparentWind((new PolarVector())->setR($aws)->setOmega(deg2rad($awa)));
        $result = $this->mapper->testResult();
        $this->assertEqualsWithDelta( $expectedTws, $result['tws'], 0.9,'tws');
        $this->assertEqualsWithDelta( $expectedTwd , rad2deg($result['twd']) ,0.9,'twd');
        $this->assertEqualsWithDelta( $expectedTwa , rad2deg($result['twa']) ,0.9,'twa');

    }

    public function testBla()
    {
        $course =  (new PolarVector())->setR(10)->setOmega(deg2rad(50));
        $speedWind =  $course->againstVector(true);
        $apparentWind = (new PolarVector())->setR(10)->setOmega(deg2rad(-170));
        $rotadeDirection = $course->getOmega(EnumRange::G180) / abs($course->getOmega(EnumRange::G180));
        $apparentWind2 = $apparentWind->rotate($course->getOmega(EnumRange::G360), true);
        $trueWind = (new PolarVectorOperation())($speedWind, $apparentWind2);

        #$this->assertEqualsWithDelta( 10, $speedWind->getR(), 0.1,'agv');
        #$this->assertEqualsWithDelta( 9.99999, rad2deg($speedWind->getOmega()), 0.1,'agv');

        #$this->assertEqualsWithDelta( 10, $apparentWind->getR(), 0.1,'agv');
        #$this->assertEqualsWithDelta( 90 , $this->rad2deg($apparentWind->getOmega()) ,0.1,'agv');

        #$this->assertEqualsWithDelta( 10, $apparentWind2->getR(), 0.1,'agv');
        #$this->assertEqualsWithDelta( 280 , rad2deg($apparentWind2->getOmega()) ,0.1,'agv');



        $this->assertEqualsWithDelta( 19.9, $trueWind->getR(), 0.1,'agv');
        $this->assertEqualsWithDelta( 235 , $this->rad2deg($trueWind->getOmega()) ,0.1,'agv');

    }


    private function msToKn(float $ms):float
    {
        return round($ms * 1.94384 , 1);
    }
    private function kn2ms(float $kn):float
    {
        return $kn / 1.94384;
    }

    private function deg2Rad($deg):float
    {
        return round(deg2rad($deg),0);
    }

    private function rad2deg($deg):float
    {
        return round(rad2deg($deg),0);
    }
}

