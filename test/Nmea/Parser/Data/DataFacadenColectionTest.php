<?php

namespace TestsNmea\Parser\Data;

use Nmea\Parser\Data\DataFacadenColection;
use PHPUnit\Framework\TestCase;

class DataFacadenColectionTest extends TestCase
{
    private $colection;

    public function testCollection()
    {
        $this->colection = new DataFacadenColection();

    }
}
