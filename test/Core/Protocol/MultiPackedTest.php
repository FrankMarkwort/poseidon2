<?php

namespace TestCore\Protocol;

use Core\Cache\CacheInterface;
use Core\Cache\ArrayCache;
use Core\Cache\Memcached;
use Core\Config\ConfigException;
use Core\Parser\DataFacadeFactory;
use Core\Protocol\FramesFactory;
use PHPUnit\Framework\TestCase;

class MultiPackedTest extends TestCase
{
    private string $filename = __DIR__ . '/../../TestData/yachtDeviceRawWithNavi001.log';

    private FramesFactory $framesFactory;
    private CacheInterface $cache;

    protected function setUp(): void
    {
        #$this->cache = new Memcached();
        $this->cache = new ArrayCache();
        $this->cache->clear();

        FramesFactory::reset();
        FramesFactory::setCache($this->cache);
    }

    public function testRead()
    {
        $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        $this->assertEquals('09:44:41.045 R 09FD0270 00 10 01 D9 EB FA FF FF', $this->cache->get('130306'));
    }

    private function testDataFacade()
    {
        $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        fclose($handle);
        $dataFacaden = [];
        foreach ($this->cache->getAll() as $key => $nmea200) {
            try {
                $dataFacade= DataFacadeFactory::create($nmea200, 'YACHT_DEVICE' );
                echo $dataFacade->getPng() .' => ' .$nmea200 . " \n";
                echo $dataFacade->getDescription() . "\n";
                foreach ($dataFacade->getOrderIds() as $orderId) {
                    echo $orderId . ' => '. $dataFacade->getFieldValue($orderId)->getName() . " => ";
                    echo $dataFacade->getFieldValue($orderId)->getResolution() . ' ';
                    echo $dataFacade->getFieldValue($orderId)->getValue() . ' => ';
                    echo $dataFacade->getFieldValue($orderId)->getValueWhithUnit() . ' => ';
                    echo $dataFacade->getFieldValue($orderId)->getValue() . ' ';
                     echo $dataFacade->getFieldValue($orderId)->getUnit() .
                         "\n\n";
                }
                $dataFacaden[$dataFacade->getPng()] = $dataFacade;

            } catch (ConfigException $e) {
                echo $e->getMessage() . " !!!! \n";
            }
        }
    }

    public function testWaterDeep()
    {
        $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        fclose($handle);

        $nmea2000 = $this->cache->get('128267');
        $dataFacade= DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE' );
        $description =  $dataFacade->getDescription();
        $tiefeUnterSensor = $dataFacade->getFieldValue(2)->getValue();
        $tiefeOffset =  $dataFacade->getFieldValue(3)->getValue();
        $tiefeWasserlinie = $tiefeUnterSensor + $tiefeOffset . $dataFacade->getFieldValue(2)->getUnit();
        $this->assertEquals('5.604m', $tiefeWasserlinie);
    }

    public function testWindData()
    {
        $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        fclose($handle);

        $nmea2000 = $this->cache->get('130306');
        $dataFacade= DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE' );
        $description =  $dataFacade->getDescription();
        $windSpeedMS = $dataFacade->getFieldValue(2)->getValue();
        $windSpeedKnoten  = (int) round($dataFacade->getFieldValue(2)->getValue() * 1.944, 0);
        $windrichtungRad =  $dataFacade->getFieldValue(3)->getValue();
        $windrichtungGrad =  (int) round(rad2deg($dataFacade->getFieldValue(3)->getValue()),0);
        $reference = $dataFacade->getFieldValue(4)->getValue();

        $this->assertEquals('Wind Data', $description);
        $this->assertEquals(2.72, $windSpeedMS);
        $this->assertEquals(5, $windSpeedKnoten);
        $this->assertEquals(6.0377, $windrichtungRad);
        $this->assertEquals(346, $windrichtungGrad);
        $this->assertEquals('Apparent', $reference);
    }

    public function testTemperature()
    {
        $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        fclose($handle);

        $nmea2000 = $this->cache->get('130312');
        $dataFacade= DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE' );

        $this->assertEquals('Temperature', $dataFacade->getDescription());
        $this->assertEquals('SID', $dataFacade->getFieldValue(1)->getName());
        $this->assertEquals('0', $dataFacade->getFieldValue(1)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(1)->getUnit());

        $this->assertEquals('Instance', $dataFacade->getFieldValue(2)->getName());
        $this->assertEquals(0, $dataFacade->getFieldValue(2)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(2)->getUnit());
        $this->assertEquals(0, $dataFacade->getFieldValue(2)->getValue());
        $this->assertEquals(0, $dataFacade->getFieldValue(2)->getValueWhithUnit());
        $this->assertEquals('Integer', $dataFacade->getFieldValue(2)->getType());

        $this->assertEquals('Source', $dataFacade->getFieldValue(3)->getName());
        $this->assertEquals('Sea Temperature', $dataFacade->getFieldValue(3)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(3)->getUnit());
        $this->assertEquals('Sea Temperature', $dataFacade->getFieldValue(3)->getValue());
        $this->assertEquals('Sea Temperature', $dataFacade->getFieldValue(3)->getValueWhithUnit());
        $this->assertEquals('Lookup table', $dataFacade->getFieldValue(3)->getType());

        $this->assertEquals('Actual Temperature', $dataFacade->getFieldValue(4)->getName());
        $this->assertEquals(295.55, $dataFacade->getFieldValue(4)->getValue());
        $this->assertEquals('K', $dataFacade->getFieldValue(4)->getUnit());
        $this->assertEquals(295.55, $dataFacade->getFieldValue(4)->getValue());
        //Celsius
        $this->equalToWithDelta(22.4, ($dataFacade->getFieldValue(4)->getValue() - 273.15));
        $this->assertEquals('295.55 K', $dataFacade->getFieldValue(4)->getValueWhithUnit());
        $this->assertEquals('Temperature', $dataFacade->getFieldValue(4)->getType());

        $this->assertEquals('Set Temperature', $dataFacade->getFieldValue(5)->getName());
        $this->assertEquals(null, $dataFacade->getFieldValue(5)->getValue());
        $this->assertEquals('K', $dataFacade->getFieldValue(5)->getUnit());
        $this->assertEquals(null, $dataFacade->getFieldValue(5)->getValue());
        //Celsius
        $this->equalToWithDelta(22.4, ($dataFacade->getFieldValue(5)->getValue() - 273.15));
        $this->assertEquals(' K', $dataFacade->getFieldValue(5)->getValueWhithUnit()); //TODO must be null
        $this->assertEquals('Temperature', $dataFacade->getFieldValue(5)->getType());

        $this->assertEquals('Reserved', $dataFacade->getFieldValue(6)->getName());
        $this->assertEquals(255, $dataFacade->getFieldValue(6)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(6)->getUnit());
        $this->assertEquals(255, $dataFacade->getFieldValue(6)->getValue());
        $this->assertEquals('255', $dataFacade->getFieldValue(6)->getValueWhithUnit());
        $this->assertEquals(null, $dataFacade->getFieldValue(6)->getType());
    }

    public function testSpeedWaterReferenced()
    {
          $handle = fopen($this->filename, 'r');
        while ($line = fgets($handle)) {
            FramesFactory::addData($line);
        }
        fclose($handle);

        $nmea2000 = $this->cache->get('128259');
        $dataFacade= DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE' );

        $this->assertEquals('Speed', $dataFacade->getDescription());

        $this->assertEquals('SID', $dataFacade->getFieldValue(1)->getName());
        $this->assertEquals('0', $dataFacade->getFieldValue(1)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(1)->getUnit());
        $this->assertEquals(0, $dataFacade->getFieldValue(1)->getValue());
        $this->assertEquals(0, $dataFacade->getFieldValue(1)->getValueWhithUnit());
        $this->assertEquals('Integer', $dataFacade->getFieldValue(1)->getType());

        $this->assertEquals('Speed Water Referenced', $dataFacade->getFieldValue(2)->getName());
        $this->assertEquals('0', $dataFacade->getFieldValue(2)->getValue());
        $this->assertEquals('m/s', $dataFacade->getFieldValue(2)->getUnit());
        $this->assertEquals(0, $dataFacade->getFieldValue(2)->getValue());
        $this->assertEquals('0 m/s', $dataFacade->getFieldValue(2)->getValueWhithUnit());
        $this->assertEquals('Number', $dataFacade->getFieldValue(2)->getType());

        $this->assertEquals('Speed Ground Referenced', $dataFacade->getFieldValue(3)->getName());
        $this->assertEquals(null, $dataFacade->getFieldValue(3)->getValue());
        $this->assertEquals('m/s', $dataFacade->getFieldValue(3)->getUnit());
        $this->assertEquals(null, $dataFacade->getFieldValue(3)->getValue());
        $this->assertEquals(0, $dataFacade->getFieldValue(3)->getValue() * 1.944);
        //TODO nust be null
        $this->assertEquals(' m/s', $dataFacade->getFieldValue(3)->getValueWhithUnit());
        $this->assertEquals('Number', $dataFacade->getFieldValue(3)->getType());

        $this->assertEquals('Speed Water Referenced Type', $dataFacade->getFieldValue(4)->getName());
        $this->assertEquals('Paddle wheel', $dataFacade->getFieldValue(4)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(4)->getUnit());
        $this->assertEquals('Paddle wheel', $dataFacade->getFieldValue(4)->getValue());
        $this->assertEquals('Paddle wheel', $dataFacade->getFieldValue(4)->getValueWhithUnit());
        #$this->assertEquals(null, $dataFacade->getFieldValue(4)->getEnum());
        $this->assertEquals('Lookup table', $dataFacade->getFieldValue(4)->getType());

        $this->assertEquals('Speed Direction', $dataFacade->getFieldValue(5)->getName());
        $this->assertEquals(null, $dataFacade->getFieldValue(5)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(5)->getUnit());
        $this->assertEquals(null, $dataFacade->getFieldValue(5)->getValueWhithUnit());
        $this->assertEquals('Integer', $dataFacade->getFieldValue(5)->getType());

        $this->assertEquals('Reserved', $dataFacade->getFieldValue(6)->getName());
        $this->assertEquals(4095, $dataFacade->getFieldValue(6)->getValue());
        $this->assertEquals(null, $dataFacade->getFieldValue(6)->getUnit());
        $this->assertEquals(4095, $dataFacade->getFieldValue(6)->getValue());
        $this->assertEquals('4095', $dataFacade->getFieldValue(6)->getValueWhithUnit());
        $this->assertEquals(null, $dataFacade->getFieldValue(6)->getType());
    }
}
