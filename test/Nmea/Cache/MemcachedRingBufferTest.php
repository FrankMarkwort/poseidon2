<?php

namespace TestsNmea\Cache;

use Nmea\Cache\Dummy;
use Nmea\Cache\Memcached;
use Nmea\Cache\MemcachedRingBuffer;
use PHPUnit\Framework\TestCase;

class MemcachedRingBufferTest extends TestCase
{

    private MemcachedRingBuffer $ringBuffer;

    protected function setUp():void
    {
        $this->ringBuffer = new MemcachedRingBuffer((new Memcached('172.17.0.1', 11211))->clear(), 3);
    }

    public function testAddLine()
    {

        $this->ringBuffer->addValue('line1');
        $this->assertEquals('line1', $this->ringBuffer->getValue());
        $this->ringBuffer->addValue('line2');
        $this->ringBuffer->addValue('line3');
        $this->assertEquals('line2', $this->ringBuffer->getValue());
        $this->ringBuffer->addValue('line4');
        $this->ringBuffer->addValue('line5');
        $this->ringBuffer->addValue('line6');
        $this->ringBuffer->addValue('line7');
        $this->assertEquals('line5', $this->ringBuffer->getValue());
        $this->assertEquals('line6', $this->ringBuffer->getValue());
        $this->ringBuffer->addValue('line8');
        $this->ringBuffer->addValue('line9');
        $this->ringBuffer->addValue('line10');
        $this->assertEquals('line8', $this->ringBuffer->getValue());
        $this->assertEquals('line9', $this->ringBuffer->getValue());
        $this->assertEquals('line10', $this->ringBuffer->getValue());
        $this->assertEquals(null, $this->ringBuffer->getValue());
    }
}
