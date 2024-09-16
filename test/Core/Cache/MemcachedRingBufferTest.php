<?php

namespace TestsCore\Cache;

use Core\Cache\ArrayCache;
use Core\Cache\Memcached;
use Core\Cache\MemcachedRingBuffer;
use Core\Config\Config;
use PHPUnit\Framework\TestCase;

class MemcachedRingBufferTest extends TestCase
{

    private MemcachedRingBuffer $ringBuffer;

    protected function setUp():void
    {
        $this->ringBuffer = new MemcachedRingBuffer(new ArrayCache(), 3);
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
