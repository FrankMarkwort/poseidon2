<?php

namespace TestsNmea\Parser;

use Nmea\Config\Config;
use Nmea\Config\ConfigFactory;
use Nmea\Config\PngFieldConfig;
use Nmea\Parser\Data\Data;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\Data\DataPart;
use Nmea\Parser\Data\MainPart;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\Decode\DecodeNmea2000;
use Nmea\Parser\Lib\BinDec;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DataFacade::class)]
#[CoversClass(DataFacadeFactory::class)]
#[CoversClass(Config::class)]
#[CoversClass(ConfigFactory::class)]
#[CoversClass(PngFieldConfig::class)]
#[CoversClass(Data::class)]
#[CoversClass(DataPart::class)]
#[CoversClass(MainPart::class)]
#[CoversClass(DecodeNmea2000::class)]
#[CoversClass(BinDec::class)]
class DataFacadeTest extends TestCase
{
    private DataFacade $dataFacade;
    protected function setUp(): void
    {
        $nmea2000Data = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $this->dataFacade = DataFacadeFactory::create($nmea2000Data,DataFacadeFactory::NONE_DEVICE);
    }

    public function testGetTimestamp()
    {
        $this->assertEquals('2011-11-24-22:42:04.388', $this->dataFacade->getTimestamp());
    }

    public function testGetPrio()
    {
         $this->assertEquals(2, $this->dataFacade->getPrio());
    }

    public function testGetPng()
    {
        $this->assertEquals('127251', $this->dataFacade->getPng());
    }

    public function testGetDescription()
    {
        #$this->dataFacade->
         $this->assertEquals('Rate of Turn', $this->dataFacade->getDescription());
    }

    public function testGetDst()
    {
        $this->assertEquals(255, $this->dataFacade->getDst());
    }

    public function testGetDataPage()
    {
         $this->assertEquals(null, $this->dataFacade->getDataPage());
    }

    public function testGetLength()
    {
        $this->assertEquals(8, $this->dataFacade->getLength());
    }

    public function testGetSrc()
    {
        $this->assertEquals(36, $this->dataFacade->getSrc());
    }

    public function testGetPduFormat()
    {
        $this->assertEquals(null, $this->dataFacade->getPduFormat());
    }

    public function testGetFieldValue()
    {
        $data = $this->dataFacade->getFieldValue(1);
        $this->assertInstanceOf(Data::class, $data);
    }

    public function testCount()
    {
        $this->assertEquals(3, $this->dataFacade->count());
    }
}
