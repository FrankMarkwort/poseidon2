<?php

namespace Core\Protocol\Frames\Frame\Data;

use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    private Data $data;
    protected function setUp():void
    {
        $this->data = new Data('E0 17 A3 99 04 80 05 02', 'R', '09:44:38.604');
    }

    public function testGetDirection()
    {
        $this->assertEquals('R', $this->data->getDirection());
    }

    public function testGetDataBytes()
    {
        $this->assertEquals(['E0', '17', 'A3', '99',  '04', '80', '05', '02'], $this->data->getDataBytes());
    }

    public function testGetFirstByte()
    {
        $this->assertEquals('E0', $this->data->getFirstByte());
    }

    public function testGetTimestamp()
    {
        $this->assertEquals('09:44:38.604', $this->data->getTimestamp());
    }

    public function testGetSecondByte()
    {
        $this->assertEquals('17', $this->data->getSecondByte());
    }
}
