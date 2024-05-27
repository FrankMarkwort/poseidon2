<?php

namespace TestsNmea\Parser\Lib;

use Nmea\Parser\Lib\BinDec;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(BinDec::class)]
class BinDecTest extends TestCase
{

    public function testBin2dec()
    {
        $this->assertEquals(1, BinDec::bin2dec('0001', true));
        $this->assertEquals(1, BinDec::bin2dec('0001', false));
        $this->assertEquals(2, BinDec::bin2dec('0010', true));
        $this->assertEquals(2, BinDec::bin2dec('0010', false));
        $this->assertEquals(3, BinDec::bin2dec('0011', true));
        $this->assertEquals(3, BinDec::bin2dec('0011', false));
        $this->assertEquals(7, BinDec::bin2dec('0111', true));
        $this->assertEquals(7, BinDec::bin2dec('0111', false));
        $this->assertEquals(-1, BinDec::bin2dec('1111', true));
        $this->assertEquals(15, BinDec::bin2dec('1111', false));
        $this->assertEquals(-7, BinDec::bin2dec('1001', true));
        $this->assertEquals(9, BinDec::bin2dec('1001', false));
        $this->assertEquals(-6, BinDec::bin2dec('1010', true));
        $this->assertEquals(10, BinDec::bin2dec('1010', false));
        $this->assertEquals(-5, BinDec::bin2dec('1011', true));
        $this->assertEquals(11, BinDec::bin2dec('1011', false));
        $this->assertEquals(22, BinDec::bin2dec('1011', false, 2));
    }

    public static function dataProviderbindec64BitSame():array
    {
        return [
            ['1111'],
            ['11111111'],
            ['1111111111111111'],
            ['11111111111111111111111111111111'],
            ['1111111111111111111111111111111111111111111111111111111111111111'],
            ['0111011111110001111001111110001111111011111111110111111111111111']
        ];
    }
    #[DataProvider('dataProviderbindec64BitSame')]
    public function testInternelAgainst64BitSame(string $bin)
    {
        $this->assertEquals(bindec($bin), BinDec::bin2dec64BitSystem($bin), strlen($bin));
    }
    public static function dataProvider32And64BitSame():array
    {
        return [
            ['1111'],
            ['11111111'],
            ['1111111111111111'],
            ['11111111111111111111111111111111'],
            ['1111111111111111111111111111111111111111111111111111111111111111'],
            ['1111', true],
            ['11111111',true],
            ['1111111111111111',true],
            ['11111111111111111111111111111111',true],
            ['1111111111111111111111111111111111111111111111111111111111111111',true],
            ['1111', true],
            ['11111111',true],
            ['1111111111111111',true],
            ['11111111111111111111111111111111',true],
            ['1111111111111111111111111111111111111111111111111111111111111111',true, 1E-16],
            ['0000010100100111000110011101010111110100010111111100001000000000',true, 1E-16],
            ['1000000000000000000000000000000000000000000000000000000000000000',true, 0,001]
        ];
    }
    #[DataProvider('dataProvider32And64BitSame')]
    public function test32And64BitSame(string $bin, bool $signed = false, float $resolution = 1)
    {
        $this->assertEquals(BinDec::bin2dec64BitSystem($bin, $signed, $resolution), BinDec::bin2dec32BitSystem($bin, $signed, $resolution), strlen($bin));
    }

    public function testResult32BitSystem()
    {
        $this->assertEquals(37.1293901, BinDec::bin2dec32BitSystem('0000010100100111000110011101010111110100010111111100001000000000', true, 1E-16));
        $this->assertEquals(37.1293901, BinDec::bin2dec64BitSystem('0000010100100111000110011101010111110100010111111100001000000000', true, 1E-16));
    }

    public function testIsNotBinaryException()
    {
        $this->expectException('\Exception');
        BinDec::bin2dec('0a01', true);
    }

     public function testWrongLengt()
    {
        $this->expectException('\Exception');
        BinDec::bin2dec('00011', true);
    }
}
