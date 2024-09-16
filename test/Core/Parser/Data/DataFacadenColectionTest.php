<?php

namespace TestCore\Parser\Data;

use Core\Parser\Data\DataFacadenColection;
use Core\Parser\DataFacadeFactory;
use PHPUnit\Framework\TestCase;

class DataFacadenColectionTest extends TestCase
{
    private $colection;

    public function testCollection()
    {

        $this->colection = new DataFacadenColection();
        $nmea2000Data = '2011-11-24-22:42:04.388,2,129029,84,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $dataFasade = DataFacadeFactory::create($nmea2000Data);
        $this->colection->add($dataFasade);
        $nmea2000Data = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $dataFasade = DataFacadeFactory::create($nmea2000Data,DataFacadeFactory::NONE_DEVICE);
        $this->colection->add($dataFasade);
        foreach ($this->colection as $key => $data) {
            if ($key == 0) {
                $this->assertEquals(255, $data->getDst());
                $this->assertEquals(84, $data->getSrc());
                $this->assertEquals('2011-11-24-22:42:04.388', $data->getTimestamp());
                $this->assertEquals(129029, $data->getPng());
                $this->assertEquals(8, $data->getLength());
                $this->assertEquals('SID', $data->getFieldValue(1)->getName());
                $this->assertEquals('Date', $data->getFieldValue(2)->getName());
                $this->assertEquals('32011', $data->getFieldValue(2)->getValue());
            } elseif ($key == 1) {
                $this->assertEquals(255, $data->getDst());
                $this->assertEquals(36, $data->getSrc());
                $this->assertEquals('2011-11-24-22:42:04.388', $data->getTimestamp());
                $this->assertEquals(127251, $data->getPng());
                $this->assertEquals(8, $data->getLength());
                $this->assertEquals('SID', $data->getFieldValue(1)->getName());
                $this->assertEquals('Rate', $data->getFieldValue(2)->getName());
                $this->assertEquals('0.00509634375', $data->getFieldValue(2)->getValue());
            }

            $this->assertEquals(2,$this->colection->count());
        }

    }
}
