<?php

namespace TestsNmea\Protocol\Frames\Frame;

use Nmea\Protocol\Frames\Frame\Data\Data;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Frames\Frame\Header\Header;
use PHPUnit\Framework\TestCase;

class FrameTest extends TestCase
{
    public function testGetHeaderData()
    {
        $frame = new Frame(new Header('19EF00FE'),new Data('E0 17 A3 99 04 80 05 02', 'R', '09:44:38.604'));
        $this->assertInstanceOf(Header::class, $frame->getHeader());
        $this->assertInstanceOf(Data::class, $frame->getData());
    }

    public function testSequenceAndFrameCounter()
    {
        $frame = new Frame(new Header('19EF00FE'),new Data('E0 17 A3 99 04 80 05 02', 'R', '09:44:38.604'));
        $this->assertEquals(7, $frame->getSequenceCounter());
        $this->assertEquals(0, $frame->getFrameCounter());
        $this->assertEquals(4, $frame->numberOfFrames());
      #  echo $frame->getHeader()
        $frame = new Frame(new Header('19EF00FE'),new Data('E1 00 01 00 00 00 07 00', 'R', '09:44:38.604'));
        $this->assertEquals(7, $frame->getSequenceCounter());
        $this->assertEquals(1, $frame->getFrameCounter());
        $this->assertEquals(-1, $frame->numberOfFrames());

        $frame = new Frame(new Header('19EF00FE'),new Data('E2 00 00 D0 84 00 00 5E', 'R', '09:44:38.604'));
        $this->assertEquals(7, $frame->getSequenceCounter());
        $this->assertEquals(2, $frame->getFrameCounter());
        $this->assertEquals(-1, $frame->numberOfFrames());

        $frame = new Frame(new Header('19EF00FE'),new Data('E3 12 00 00 FF FF FF FF', 'R', '09:44:38.604'));
        $this->assertEquals(7, $frame->getSequenceCounter());
        $this->assertEquals(3, $frame->getFrameCounter());
        $this->assertEquals(-1, $frame->numberOfFrames());
    }
}
