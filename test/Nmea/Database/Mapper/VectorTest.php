<?php

namespace TestsNmea\Database\Mapper;

use Nmea\Database\Mapper\Vector\PolarVector;
use PHPUnit\Framework\TestCase;

class VectorTest extends TestCase
{
    public function testConstruct()
    {
        $vector = new PolarVector([1, pi()]);


    }

}
