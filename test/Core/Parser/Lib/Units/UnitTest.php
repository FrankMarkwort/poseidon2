<?php

namespace TestCore\Parser\Lib\Units;

use Core\Parser\Lib\Units\Unit;
use Core\Parser\Lib\Units\UnitInterface;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testTemperature()
    {
        $unit = new Unit(70, 'K');
        $this->assertEquals('C', $unit->getMappedUnit());
        $this->assertEquals(-203.15 , $unit->getMappedValue());
    }

    public function testTemperatureFahrenheit()
    {
        $unit = new Unit(70, 'K');
        $config = [UnitInterface::SI_KELVIN => [UnitInterface::UNIT => 'F', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s F']];
        $unit->setConfig($config);
        $this->assertEquals('F', $unit->getMappedUnit());
        $this->assertEquals(-333.7, $unit->getMappedValue());
        $this->assertEquals( '-333.7 F', $unit->getMappedValueWithUnit());
    }

    public function testPressure()
    {
        $unit = new Unit(100, 'Pa');
        $this->assertEquals('hPa', $unit->getMappedUnit());
        $this->assertEquals(1, $unit->getMappedValue());
        $this->assertEquals( '1 hPa', $unit->getMappedValueWithUnit());
    }

    public function testSpeedKmH()
    {
        $config = [UnitInterface::SI_SPEED => [UnitInterface::UNIT => 'km/h', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s km/h']];
        $unit = new Unit(1, UnitInterface::SI_SPEED);
        $unit->setConfig($config);
        $this->assertEquals('km/h', $unit->getMappedUnit());
        $this->assertEquals(3.6, $unit->getMappedValue());
        $this->assertEquals('3.6 km/h', $unit->getMappedValueWithUnit());
    }

    public function testSpeedKnots()
    {
        $unit = new Unit(1, 'm/s');
        $this->assertEquals('kt', $unit->getMappedUnit());
        $this->assertEquals(1.9, $unit->getMappedValue());
    }

    public function testAngle()
    {
        $unit = new Unit(M_PI, 'rad');
        $this->assertEquals('180°', $unit->getMappedValueWithUnit());
        $this->assertEquals('180', $unit->getMappedValue());
        $this->assertEquals('grad', $unit->getMappedUnit());
    }

    public function testDate()
    {
        $unit = new Unit(5, 'd', 'date');
        $this->assertEquals('1970-01-06', $unit->getMappedValueWithUnit());
    }

    public function testTime()
    {
        $unit = new Unit(5, 's', 'date');
        $this->assertEquals('00:00:05', $unit->getMappedValueWithUnit());
    }

    public function testLongitude()
    {
        $unit = new Unit(371293900 * 1E-7, 'deg', 'Longitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(37.12939, $unit->getMappedValue());
        $this->assertEquals("37° 7' 45.8''E", $unit->getMappedValueWithUnit());
        $unit = new Unit(0, 'deg', 'Longitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(0, $unit->getMappedValue());
        $this->assertEquals("0° 0' 0''", $unit->getMappedValueWithUnit());
        $unit = new Unit(-371293900 * 1E-7, 'deg', 'Longitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(-37.12939, $unit->getMappedValue());
        $this->assertEquals("37° 7' 45.8''W", $unit->getMappedValueWithUnit());
    }


    public function testLatitude()
    {
        $unit = new Unit(371293900 * 1E-7, 'deg', 'Latitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(37.12939, $unit->getMappedValue());
        $this->assertEquals("037° 7' 45.8''N", $unit->getMappedValueWithUnit());
        $unit = new Unit(0, 'deg', 'Latitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(0, $unit->getMappedValue());
        $this->assertEquals("0° 0' 0''", $unit->getMappedValueWithUnit());
        $unit = new Unit(-371293900 * 1E-7, 'deg', 'Latitude');
        $this->assertEquals('deg', $unit->getMappedUnit());
        $this->assertEquals(-37.12939, $unit->getMappedValue());
        $this->assertEquals("037° 7' 45.8''S", $unit->getMappedValueWithUnit());
    }

    public function testUnitisNotExist()
    {
        $unit = new Unit(5, 'z');
        $this->assertEquals('z', $unit->getMappedUnit());
        $this->assertEquals(5, $unit->getMappedValue());
    }

    public function testUnitExistButNotSupported()
    {
        $config = [UnitInterface::SI_SPEED => [UnitInterface::UNIT => 'mm/h', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s mm/h']];
        $unit = new Unit(12, UnitInterface::SI_SPEED);
        $unit->setConfig($config);
        $this->assertEquals('m/s', $unit->getMappedUnit());
        $this->assertEquals(12, $unit->getMappedValue());
        $this->assertEquals('12 m/s', $unit->getMappedValueWithUnit());
        $config = [UnitInterface::SI_KELVIN => [UnitInterface::UNIT => 'mK', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s mk']];
        $unit = new Unit(12, UnitInterface::SI_KELVIN);
        $unit->setConfig($config);
        $this->assertEquals('K', $unit->getMappedUnit());
        $this->assertEquals(12, $unit->getMappedValue());
        $this->assertEquals('12 K', $unit->getMappedValueWithUnit());
        $config = [UnitInterface::SI_PASCAL => [UnitInterface::UNIT => 'mPa', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s mPa']];
        $unit = new Unit(12, UnitInterface::SI_PASCAL);
        $unit->setConfig($config);
        $this->assertEquals('Pa', $unit->getMappedUnit());
        $this->assertEquals(12, $unit->getMappedValue());
        $this->assertEquals('12 Pa', $unit->getMappedValueWithUnit());
        $config = [UnitInterface::SI_ANGEL => [UnitInterface::UNIT => 'angel', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s angel']];
        $unit = new Unit(12, UnitInterface::SI_ANGEL);
        $unit->setConfig($config);
        $this->assertEquals('rad', $unit->getMappedUnit());
        $this->assertEquals(12, $unit->getMappedValue());
        $this->assertEquals('12 rad', $unit->getMappedValueWithUnit());
    }

}
