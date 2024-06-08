<?php

namespace TestsNmea\Database\Mapper;

use Nmea\Database\Database;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Math\Vector\PolarVector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WindSpeedCourseTest extends TestCase
{
    private $mapper;
    protected function setUp(): void
    {
        $this->mapper = new WindSpeedCourse(Database::getInstance());
    }

    public static function dataProvider()
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

    #[DataProvider('dataProvider')]
    public function testFront(float $aws, float $awa, float $sog, float $cog, float $heading,float $twd,float $tws,float $twa)
    {
         $this->mapper->setTime('now')->setWaterTemperature(22);

         $this->mapper->setCourseOverGround((new PolarVector())->setR($this->kn2ms($sog))->setOmega(deg2rad($cog)));
         $this->mapper->setVesselHeading( (new PolarVector())->setR(0)->setOmega(deg2rad($heading)));
         $this->mapper->setApparentWind((new PolarVector())->setR($this->kn2ms($aws))->setOmega(deg2rad($awa)));

         $result = $this->mapper->getStoreArray();

        $this->assertEquals( $tws , $result[4], 'tws');
        $this->assertEquals( $twa , $result[5],'twa');
        $this->assertEquals( $twd , $result[1],'twd');
        /*
            $this->getTime(),
            $this->angleGrad($this->getTrueWind()->getOmega()), 1
            $this->msToKnots($this->getApparentWind()->getR()), 2
            $this->angleGrad($this->getApparentWind()->getOmega(Range::G180)), 3
            $this->msToKnots($this->getTrueWind()->getR()), 4 tws
            $this->angleGrad($this->getTrueWind()->getOmega(Range::G180)), 5 twa
            $this->angleGrad($this->getCourseOverGround()->getOmega()),
            $this->msToKnots($this->getCourseOverGround()->getR()),
            $this->angleGrad($this->getVesselHeading()->getOmega()),
            $this->kelvinToCelsius($this->getWaterTemperature())
        */
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

