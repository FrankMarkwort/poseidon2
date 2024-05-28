<?php
namespace TestsNmea\Parser\Data;

use Nmea\Config\Config;
use Nmea\Config\ConfigFactory;
use Nmea\Config\PngFieldConfig;
use Nmea\Parser\Data\Data;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\Data\DataPart;
use Nmea\Parser\Data\MainPart;
use Nmea\Parser\Decode\DecodeNmea2000;
use Nmea\Parser\Lib\BinDec;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Nmea\Parser\DataFacadeFactory;

#[CoversClass(DataFacadeFactory::class)]
#[CoversClass(Config::class)]
#[CoversClass(ConfigFactory::class)]
#[CoversClass(PngFieldConfig::class)]
#[CoversClass(Data::class)]
#[CoversClass(DataFacade::class)]
#[CoversClass(DataPart::class)]
#[CoversClass(MainPart::class)]
#[CoversClass(DecodeNmea2000::class)]
#[CoversClass(BinDec::class)]
class DataFacadeFactoryTest extends TestCase
{
    public function testClass()
    {
        $nmea2000Data = '2011-11-24-22:42:04.388,2,129029,84,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $dataFasade = DataFacadeFactory::create($nmea2000Data);
        $this->assertEquals(255, $dataFasade->getDst());
        $this->assertEquals(84, $dataFasade->getSrc());
        $this->assertEquals('2011-11-24-22:42:04.388', $dataFasade->getTimestamp());
        $this->assertEquals(129029, $dataFasade->getPng());
        $this->assertEquals(8, $dataFasade->getLength());
        $this->assertEquals('SID', $dataFasade->getFieldValue(1)->getName());
        $this->assertEquals('Date', $dataFasade->getFieldValue(2)->getName());
        $this->assertEquals('32011', $dataFasade->getFieldValue(2)->getValue());


        $nmea2000Data = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $dataFasade = DataFacadeFactory::create($nmea2000Data,DataFacadeFactory::NONE_DEVICE);
        $this->assertEquals(255, $dataFasade->getDst());
        $this->assertEquals(36, $dataFasade->getSrc());
        $this->assertEquals('2011-11-24-22:42:04.388', $dataFasade->getTimestamp());
        $this->assertEquals(127251, $dataFasade->getPng());
        $this->assertEquals(8, $dataFasade->getLength());
        $this->assertEquals('SID', $dataFasade->getFieldValue(1)->getName());
        $this->assertEquals('Rate', $dataFasade->getFieldValue(2)->getName());
        $this->assertEquals('0.00509634375', $dataFasade->getFieldValue(2)->getValue());

//         var_dump($dataFasade);
    }
}