<?php
namespace TestsNmea\Config;

use Nmea\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @var Config */
    private $configClass;

    protected function setUp():void
    {
        $this->configClass = new Config();
    }

    public function testGetPngConfig()
    {
        $this->assertEquals(130306, $this->configClass->getPngConfig(130306)['PGN']);
        $this->assertEquals('windData', $this->configClass->getPngConfig(130306)['Id']);
        $this->assertEquals('Wind Data', $this->configClass->getPngConfig(130306)['Description']);
        $this->assertEquals('Single', $this->configClass->getPngConfig(130306)['Type']);
        $this->assertEquals(true, $this->configClass->getPngConfig(130306)['Complete']);
        $this->assertEquals(8, $this->configClass->getPngConfig(130306)['Length']);
        $this->assertEquals(0, $this->configClass->getPngConfig(130306)['RepeatingFields']);
        $this->assertTrue(is_array($this->configClass->getPngConfig(130306)['Fields']));
    }

    public function testGetDescription()
    {
        $this->assertEquals('Wind Data', $this->configClass->getDescription(130306));
    }
}