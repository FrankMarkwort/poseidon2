<?php

namespace Nmea\Deamon;

use Nmea\Cache\Dummy;
use Nmea\Cache\Memcached;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    
    public  function testBootstrap()
    {
        $modeStream = false;
        if ($modeStream) {
            $socat = 'nohup socat TCP4:172.17.0.1:1236 PTY,link=' .  __DIR__. '/./../../dev/ttyOut,raw,echo=0';
            $pid =  exec($socat . ' > /dev/null 2>&1 & echo $!');
            $bootstrap = new Bootstrap(new Serial(__DIR__ . '/./../../dev/ttyOut'), (new Memcached('172.17.0.1', 11211))->clear());
            exec("kill -16 $pid");
        } else {
            $bootstrap = new Bootstrap(new Serial( __DIR__ . '/../../TestData/data.log'), (new Memcached('172.17.0.1', 11211))->clear());
        }
        $bootstrap->run();

    }
}
