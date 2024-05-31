<?php

namespace TestsNmea\Database\Mapper;

use Nmea\Database\Mapper\WindSpeedCourse;
use PHPUnit\Framework\TestCase;

class WindSpeedCourseTest extends TestCase
{

    public function testSetCogReference()
    {
        for($i = 1; $i <= 3600; $i++){
            #sleep(24 - date('i')  % 24);
            var_dump((24 * 60 - $i * 60)  % (24 * 60));
            #echo date('H:i:s') . "\n";
        }
    }
}
