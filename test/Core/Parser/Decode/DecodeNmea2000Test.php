<?php
namespace TestCore\Parser\Decode;

use Core\Parser\Decode\Request;
use Math\Bin\BinDec;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Core\Parser\Decode\DecodeNmea2000;

#[CoversClass(DecodeNmea2000::class)]
#[CoversClass(BinDec::class)]
class DecodeNmea2000Test extends TestCase
{
    public function testReturn()
    {
        $nmea2000Data = '7d,0b,7d,02,00,ff,ff,ff';
        $dataArray = explode(',', $nmea2000Data);
        $instance = DecodeNmea2000::getInstance();
        $instance->setArray($dataArray, 8);
        $instance->setArray($dataArray, 8);
        $request = new Request();
        $request->setBitOffset(0)
            ->setBitLength(8)
            ->setType('Integer')
            ->setSignet(false)
            ->setResolution(1)
            ->setRangeMin(1)
            ->setRangeMax(10);

        $this->assertEquals( $instance->getValue($request), $instance->getValue($request));
    }
}