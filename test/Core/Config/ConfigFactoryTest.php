<?php

namespace TestCore\Config;

use Core\Config\ConfigFactory;
use PHPUnit\Framework\TestCase;

class ConfigFactoryTest extends TestCase
{
    public function testGetPngConfig()
    {
        $config = ConfigFactory::create(127251);
        $this->assertEquals(8, $config->getBitLength(1));
        $this->assertEquals(0, $config->getBitOffset(1));
        $this->assertEquals(0, $config->getBitStart(1));
        $this->assertEquals(1, $config->getResolution(1));
        $this->assertEquals(0, $config->getSigned(1));
        $this->assertEquals('', $config->getUnits(1));

        $this->assertEquals(32, $config->getBitLength(2));
        $this->assertEquals(8, $config->getBitOffset(2));
        $this->assertEquals(0, $config->getBitStart(2));
        $this->assertEquals(3.125E-8, $config->getResolution(2));
        $this->assertEquals(true, $config->getSigned(2));
        $this->assertEquals('rad/s', $config->getUnits(2));

        $config = ConfigFactory::create(127250);
        $this->assertEquals(8, $config->getBitLength(1));
        $this->assertEquals(0, $config->getBitOffset(1));
        $this->assertEquals(0, $config->getBitStart(1));
        $this->assertEquals(1, $config->getResolution(1));
        $this->assertEquals(0, $config->getSigned(1));
        $this->assertEquals('', $config->getUnits(1));

        $this->assertEquals(16, $config->getBitLength(2));
        $this->assertEquals(8, $config->getBitOffset(2));
        $this->assertEquals(0, $config->getBitStart(2));
        $this->assertEquals(0.0001, $config->getResolution(2));
        $this->assertEquals(false, $config->getSigned(2));
        $this->assertEquals('rad', $config->getUnits(2));
    }
}
