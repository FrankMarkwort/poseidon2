<?php
namespace Nmea\Parser\Data;

use Nmea\Parser\Data\DataPart;
use Nmea\Parser\Decode\DecodeNmea2000;
use Nmea\Config\Config;
use Nmea\Config\PngFieldConfig;
use Nmea\Parser\Lib\BinDec;
use PHPUnit\Framework\Attributes\CoversClass;

use PHPUnit\Framework\TestCase;

#[CoversClass(DataPart::class)]
#[CoversClass(Config::class)]
#[CoversClass(PngFieldConfig::class)]
#[CoversClass(Data::class)]
#[CoversClass(MainPart::class)]
#[CoversClass(DecodeNmea2000::class)]
#[CoversClass(BinDec::class)]
final class DataPartTest extends TestCase
{
    /**
     * @var DataPart
     */
    private $dataPart;
    private $mainPart;
    private $pngConfig;

    protected function setUp():void
    {
        $testData = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $this->mainPart = new MainPart('NONE');
        $this->mainPart->setMainBitString($testData);
        $this->pngConfig = (new PngFieldConfig())
            ->setConfigInstance(new Config())
            ->setPgn($this->mainPart->getPng());
        $this->dataPart =(new DataPart())->setDecoder(DecodeNmea2000::getInstance())->setData($this->mainPart->getData(),$this->mainPart->getLength(),$this->pngConfig);
    }

    public function testGetFieldValuesAfterInit()
    {
        $this->assertEquals('SID',$this->dataPart->getFieldValue(1)->getName());
        $this->assertEquals(125, $this->dataPart->getFieldValue(1)->getValue());
        $this->assertEquals('rad/s', $this->dataPart->getFieldValue(2)->getUnit());
    }

    public function testGetFieldValuesAfterUpdate()
    {
        $testData = '2011-11-24-22:42:04.388,2,130306,36,255,8,ff,2c,01,a3,16,fa,ff,ff';
        $this->mainPart->setMainBitString($testData);
        $this->dataPart->setData(
            $this->mainPart->getData(),
            $this->mainPart->getLength(),
            $this->pngConfig->setPgn($this->mainPart->getPng())
        );

        $this->assertEquals('SID',$this->dataPart->getFieldValue(1)->getName());
        $this->assertEquals(null, $this->dataPart->getFieldValue(1)->getValue());
        $this->assertEquals('Wind Speed', $this->dataPart->getFieldValue(2)->getName());
        $this->assertEquals(3.0, $this->dataPart->getFieldValue(2)->getValue());
        $this->assertEquals(3.0, $this->dataPart->getFieldValue(2)->getValue());
        $this->assertEquals('3 m/s', $this->dataPart->getFieldValue(2)->getValueWhithUnit());
        $this->assertEquals('Wind Angle', $this->dataPart->getFieldValue(3)->getName());
        $this->assertEquals(0.5795, $this->dataPart->getFieldValue(3)->getValue());
        $this->assertEquals(0.5795, $this->dataPart->getFieldValue(3)->getValue());
        $this->assertEquals('0.5795 rad', $this->dataPart->getFieldValue(3)->getValueWhithUnit());
        $this->assertEquals('Reference', $this->dataPart->getFieldValue(4)->getName());
        $this->assertEquals('Apparent', $this->dataPart->getFieldValue(4)->getValue());
        $this->assertEquals('Apparent', $this->dataPart->getFieldValue(4)->getValue());
        #print_r($this->dataPart2->getFieldValue(4)->getEnum());
        #$this->assertEquals([], $this->dataPart2->getFieldValue(4)->getEnum());
        //TODO vieleicht Null zurückgeben
        $this->assertEquals('Apparent', $this->dataPart->getFieldValue(4)->getValueWhithUnit());
    }
}
