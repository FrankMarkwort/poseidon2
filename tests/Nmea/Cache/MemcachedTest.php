<?php

namespace TestsNmea\Cache;

use PHPUnit\Framework\TestCase;
use Nmea\Cache\Memcached;

class MemcachedTest extends TestCase
{

    public function testBla()
    {
        $test = new Memcached('172.17.0.1', 11211);
        $test->clear();
        $test->set('blub', 'bla');
        $test->set('blub', 'bla1');
        $test->set('blub', 'bla2');
        $this->assertEquals('bla2', $test->get('blub'));
    }
}
