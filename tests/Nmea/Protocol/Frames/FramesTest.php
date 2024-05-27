<?php

namespace TestsNmea\Protocoll\Frames;

use Nmea\Cache\Dummy;
use Nmea\Protocol\Frames\Frame\Data\Data;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Frames\Frame\Header\Header;
use Nmea\Protocol\Frames\Frames;
use PHPUnit\Framework\TestCase;

class FramesTest extends TestCase
{
    public function testBla()
    {
        $cache = new Dummy();
        $frames = new Frames($cache);
        $frame = new Frame(new Header('19EF00FE'),new Data('E0 17 A3 99 04 80 05 02', 'R', '09:44:38.604'));
        $frames->addFrame($frame);
        $frame = new Frame(new Header('19EF00FE'),new Data('E1 00 01 00 00 00 07 00', 'R', '09:44:38.604'));
        $frames->addFrame($frame);
        $frame = new Frame(new Header('19EF00FE'),new Data('E2 00 00 D0 84 00 00 5E', 'R', '09:44:38.604'));
        $frames->addFrame($frame);
        $frame = new Frame(new Header('19EF00FE'),new Data('E3 12 00 00 FF FF FF FF', 'R', '09:44:38.604'));
        $frames->addFrame($frame);
        // singleFrame
        $frame = new Frame(new Header('9ED00FE'),new Data('E3 12 00 00 FF FF FF FF', 'R', '09:44:38.604'));
        $frames->addFrame($frame);

        $this->assertEquals(
            '09:44:38.604 R 19EF00FE A3 99 04 80 05 02 00 01 00 00 00 07 00 00 00 D0 84 00 00 5E 12 00 00 FF FF FF FF',
            $cache->get('126720')
        );
        $this->assertEquals(
            '09:44:38.604 R 9ED00FE E3 12 00 00 FF FF FF FF',
            $cache->get('126208')
        );
    }
}
