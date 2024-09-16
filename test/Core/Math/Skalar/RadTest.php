<?php

namespace TestCore\Math\Skalar;

use Core\Math\EnumRange;
use Core\Math\Skalar\Rad;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RadTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            'G360 0' => [EnumRange::G360 ,0.0 , 0.0],
            'G360 90' => [EnumRange::G360 ,90 , 90],
            'G360 180' => [EnumRange::G360 ,180 , 180 ],
            'G360 270' => [EnumRange::G360 ,270 , 270],
            'G360 360' => [EnumRange::G360 ,360, 0 ],
            'G360 450' => [EnumRange::G360 ,450, 90 ],
            'G360 -0' => [EnumRange::G360 ,-0.0 , 0.0],
            'G360 -90' => [EnumRange::G360 ,-90 , 270 ],
            'G360 -180' => [EnumRange::G360 ,-180 , 180 ],
            'G360 -270' => [EnumRange::G360 ,-270, 90 ],
            'G360 -360' => [EnumRange::G360 ,-360, 0 ],
            'G360 -450' => [EnumRange::G360 ,-450, 270 ],

            'G180 0' => [EnumRange::G180 ,0.0 , 0.0],
            'G180 p90' => [EnumRange::G180 , 90, 90 ],
            'G180 120' => [EnumRange::G180 , 120, 120 ],
            'G180 180' => [EnumRange::G180 ,180 , 180 ],
            'G180 360' => [EnumRange::G180 , 360, 0 ],
            'G180 270' => [EnumRange::G180 , 270, -90 ],
            'G180 450' => [EnumRange::G180 , 450, 90 ],
            'G180 540' => [EnumRange::G180 ,540, 180.0],
            'G180 -0' => [EnumRange::G180 ,-0.0 , 0.0],

            'G180 -10' => [EnumRange::G180 , -10 , -10 ],
            'G180 -90' => [EnumRange::G180 , -90 , -90 ],
            'G180 -120' => [EnumRange::G180, -120 , -120 ],
            'G180 -180' => [EnumRange::G180 , -180 , 180 ],
            'G180 -270' => [EnumRange::G180 , -270 , 90 ],
            'G180 -360' => [EnumRange::G180, -360, 0 ],
            'G180 -450' => [EnumRange::G180, -450, -90 ],
            'G180 -540' => [EnumRange::G180, -540, 180 ]

        ];
    }

    #[DataProvider('dataProvider')]
    public function testGetRad(EnumRange $range, float $actual, float $expected)
    {
        $rad = new Rad();
        $rad->setOmega(deg2rad($actual));
        $this->assertEquals($expected, rad2deg($rad->getOmega($range)), $actual);
        $this->assertEquals($rad->getDegOmega($range), rad2deg($rad->getOmega($range)));
    }
}
