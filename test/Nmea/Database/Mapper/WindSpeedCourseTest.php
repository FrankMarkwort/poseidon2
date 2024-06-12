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
            # [10.00 , 45.00 , 10.00 , 45.00, 7.7, 113.00, 158],
            # [10.00 , 50.00 , 10.00 , 45.00, 7.7, 113.00, 163],
            # [10.00 , 50 , 10.00 , 170, 19.9, 175, 225],
            # [10.00 , 50 , 10.00 , 180, 20, 180, 230],
            # [10.00 , 170 , 10.00 , 92, 14.4, 136, 306],
             [ 10.00 , 50 , 10.00 , -170, 19.9, 175, 225],

        ];

    }

    #[DataProvider('dataProvider')]
    public function testFormula(float $sog, float $heading, float $aws, float $awa,float $expectedTws,float $expectedTwa,float $expectedTwd)
    {
        $tws = sqrt( pow($sog,2) + pow($aws,2) - (2 * $aws * $sog * cos(deg2rad($awa))));
        $beta = acos((pow($aws,2) -  pow($tws,2) - pow($sog,2)) / ( 2 * $tws * $sog));

        $twd = $beta + deg2rad($heading);

        $this->assertEqualsWithDelta( $expectedTws , $tws, 0.1,'tws');
        $this->assertEquals( $expectedTwd , $this->rad2deg($twd) ,'twd');
        $this->assertEquals( $expectedTwa , ($this->rad2deg($twd) - $heading),'twa');

        $vesselHeading = (new Rad())->setOmega(deg2rad($heading));
        $courseOverGround = (new PolarVector())->setR($sog)->setOmega(deg2rad($heading));
        $apparentWind = (new PolarVector())->setR($aws)->setOmega(deg2rad($awa));

        $op = new PolarVectorOperation();
        #$trueWind = $op($apparentWind->rotate(deg2rad() + $vesselHeading->getOmega(EnumRange::G180)), $courseOverGround, Operator::MINUS);
        $trueWind = $op($apparentWind->rotate($vesselHeading->getOmega(EnumRange::G180)), $courseOverGround, Operator::MINUS);
        $this->assertEqualsWithDelta( $expectedTws , $trueWind->getR(), 1,'tws');
        $this->assertEquals( $expectedTwd , $this->rad2deg($trueWind->getOmega()) ,'twd');

        #var_dump($trueWind);


    }



    #[DataProvider('dataProvider')]
    public function testFront(float $sog, float $heading, float $aws, float $awa,float $tws,float $twa,float $twd)
    {
         $cog = $heading;
         $this->mapper->setTime('now')->setWaterTemperature(22);

         $this->mapper->setCourseOverGround((new PolarVector())->setR($this->kn2ms($sog))->setOmega(deg2rad($cog)));
         $this->mapper->setVesselHeading( (new Rad())->setOmega(deg2rad($heading)));
         $this->mapper->setApparentWind((new PolarVector())->setR($this->kn2ms($aws))->setOmega(deg2rad($awa)));

         $result = $this->mapper->testResult();

         $this->assertEquals( $tws , $result[0], 'tws');
         $this->assertEquals( $twa , $result[1],'twa');
         $this->assertEquals( $twd , $result[2],'twd');
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

