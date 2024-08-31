<?php
namespace TestsNmea\Config;

use Exception;
use Nmea\Config\ConfigPgn;
use Nmea\Config\PngFieldConfig;
use PHPUnit\Framework\TestCase;

class PngFieldConfigTest extends TestCase
{
    /** @var ConfigPgn */
    private $configClass;

    protected function setUp():void
    {
        $this->configClass = new ConfigPgn();
        $this->configFields = (new PngFieldConfig())
            ->setConfigInstance($this->configClass)
            ->setPgn(130306);
    }

    public function testGetFields()
    {
        $expected =[
            'Order'=> 2,
            'Id' => 'windSpeed',
            'Name'=> 'Wind Speed',
            'BitLength'=> 16,
            'BitOffset' => 8,
            'BitStart' => 0,
            'Units' => 'm/s',
            'Resolution' => 0.01,
            'Signed' =>false,
            'Type' => 'Number',
            'RangeMin' => 0,
            'RangeMax' => 655.33
        ];
        $this->assertEquals($expected,$this->configFields->getFieldsByOrder(2) );
        $expected =[
            'Order'=> 1,
            'Id' => 'sid',
            'Name'=> 'SID',
            'BitLength'=> 8,
            'BitOffset' => 0,
            'BitStart' => 0,
            'Signed' =>false,
            'Type' => 'Integer',
            'Resolution' => 1,
            'RangeMin' => 0,
            'RangeMax' => 253
        ];
        $this->assertEquals($expected,$this->configFields->getFieldsByOrder(1) );
    }

    public function testException()
    {
        $expected =[
            'Order'=> 1,
            'Id' => 'sid',
            'Name'=> 'SID',
            'BitLength'=> 8,
            'BitOffset' => 0,
            'BitStart' => 0,
            'Signed' =>false
        ];
        $this->expectException(Exception::class);
        $this->assertEquals($expected,$this->configFields->getFieldsByOrder(99) );
    }

    public function testGetSigned()
    {
        $this->assertFalse($this->configFields->getSigned(1) );
    }

    public function testBitLength()
    {
        $this->assertEquals(16, $this->configFields->getBitLength(2));
        $this->assertEquals(8, $this->configFields->getBitLength(1));
    }

    public function testBitOffset()
    {
        $this->assertEquals(8, $this->configFields->getBitOffset(2));
        $this->assertEquals(0, $this->configFields->getBitOffset(1));
    }

    public function testBitStart()
    {
        $this->assertEquals(0, $this->configFields->getBitStart(2));
        $this->assertEquals(0, $this->configFields->getBitStart(1));
    }

    public function testGetResolution()
    {
        $this->assertEquals(1, $this->configFields->getResolution(1));
    }

    public function testGetUnits()
    {
        $this->assertEquals('m/s', $this->configFields->getUnits(2));
        $this->assertNull($this->configFields->getUnits(1));
    }

    public function testGetType()
    {
        $this->assertEquals('Number', $this->configFields->getType(2));
    }

    public function testCount()
    {
         $this->assertEquals(5, $this->configFields->count());
    }
}
