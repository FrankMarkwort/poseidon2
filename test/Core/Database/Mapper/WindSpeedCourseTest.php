<?php

namespace TestCore\Database\Mapper;

use Modules\Module\Cron\WeatherStatistic\Entity\WindSpeedCourse;
use Core\Database\Database;
use Math\Skalar\Rad;
use Math\Vector\PolarVector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WindSpeedCourseTest extends TestCase
{
    private $mapper;
    protected function setUp(): void
    {
        $this->mapper = new WindSpeedCourse(Database::getInstance());
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
          #  [ 10.00 , 50 , 50 , 10.00 , -170, 19.9, 185, 235], //TODO
            [ 10.00 , 190, 190, 10.00, 90.0, 14.1, 135, 325]
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFormula(float $sog, float $cog, float $heading, float $aws, float $awa,float $expectedTws,float $expectedTwa,float $expectedTwd)
    {
        $this->mapper->setTime('timestamp')->setWaterTemperature(22);
        $this->mapper->setCourseOverGround((new PolarVector())->setR($sog)->setOmega(deg2rad($cog)));
        $this->mapper->setVesselHeading((new Rad())->setOmega(deg2rad($heading)));
        $this->mapper->setApparentWind((new PolarVector())->setR($aws)->setOmega(deg2rad($awa)));
        $result = $this->mapper->toArray();
        $this->assertEqualsWithDelta( $expectedTws,  $this->kn2ms($result['tws']), 0.9,'tws');
        $this->assertEqualsWithDelta( $expectedTwd , $result['twd'] ,0.9,'twd');
        $this->assertEqualsWithDelta( $expectedTwa , $result['twa'] ,0.9,'twa');

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

