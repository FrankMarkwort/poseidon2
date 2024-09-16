<?php

namespace TestCore\Protocol\Frames\Frame\Header;

use PHPUnit\Framework\TestCase;
use Core\Protocol\Frames\Frame\Header\Header;

class HeaderTest extends TestCase
{
    public function testPduFormat()
    {
        $header = new Header('CF004FE');
        $this->assertEquals(240, $header->getPduFormat());
        $this->assertEquals(4 , $header->getGroupExtension());
        $this->assertEquals(4, $header->getPduSpecific());
        $this->assertEquals(255, $header->getDestination());
        #$header = new Header('AAAAAA');
        #$this->assertEquals(170, $header->getPduFormat());
        #$this->assertEquals(0 , $header->getGroupExtension());
        #$this->assertEquals(170, $header->getPduSpecific());
        #$this->assertEquals(170, $header->getDestination());
    }

    public function testHeader()
    {
        $header = new Header('CF004FE');
        $this->assertEquals('CF004FE' , $header->getCanIdHex());
        $this->assertEquals(217056510, $header->getCanIdDec());
        $this->assertEquals(3, $header->getPriority());
        $this->assertEquals(0, $header->getCanExtendedId());
        $this->assertEquals(0, $header->getDataPage());
        $this->assertEquals(0, $header->getExtendedDataPage());
        $this->assertEquals(0, $header->getReserved());
        $this->assertEquals(254, $header->getSourceAdress());
        $this->assertEquals(61444, $header->getPgn());
        $this->assertEquals(true, $header->isSingelPacked());
        $this->assertEquals(false, $header->isNmea2000Packed());


        $header = new Header('98FEF4FE');
        $this->assertEquals(2566845694, $header->getCanIdDec());
        $this->assertEquals(6, $header->getPriority());
        $this->assertEquals(0, $header->getCanExtendedId());
        $this->assertEquals(0, $header->getDataPage());
        $this->assertEquals(0, $header->getExtendedDataPage());
        $this->assertEquals(0, $header->getReserved());
        $this->assertEquals(254, $header->getPduFormat());
        $this->assertEquals(244, $header->getPduSpecific());
        $this->assertEquals(254, $header->getSourceAdress());
        $this->assertEquals(65268, $header->getPgn());
        $this->assertEquals(true, $header->isSingelPacked());
        $this->assertEquals(false, $header->isNmea2000Packed());

        $header = new Header('CFE6CEE');
        $this->assertEquals(218000622, $header->getCanIdDec());
        $this->assertEquals(3, $header->getPriority());
        $this->assertEquals(0, $header->getCanExtendedId());
        $this->assertEquals(0, $header->getDataPage());
        $this->assertEquals(0, $header->getReserved());
        $this->assertEquals(254, $header->getPduFormat());
        $this->assertEquals(108, $header->getPduSpecific());
        $this->assertEquals(238, $header->getSourceAdress());
        $this->assertEquals(65132, $header->getPgn());
        $this->assertEquals(true, $header->isSingelPacked());
        $this->assertEquals(false, $header->isNmea2000Packed());
    }
    public function testIsSingleFrame()
    {
        //von
        $header = new Header('0EE04FE');
        $this->assertEquals(60928, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());
        //bis
        $header = new Header('0EE0000');
        $this->assertEquals(60928, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());

        //No range
        $header = new Header('0EFFFFF');
        $this->assertEquals(61184, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());

        //von
        $header = new Header('0F000FF');
        $this->assertEquals(61440, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());
        // bis
        $header = new Header('0FEFFFF');
        $this->assertEquals(65279, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());

        //von
        $header = new Header('8FF00FE');
        $this->assertEquals(65280, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());
        // bis
        $header = new Header('8FFFFFE');
        $this->assertEquals(65535, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());

        //von
        $header = new Header('9ED00FE');
        $this->assertEquals(126208, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());
        // bis
        $header = new Header('9EEFFFE');
        $this->assertEquals(126464, $header->getPgn());
        $this->assertTrue($header->isSingelPacked());
        $this->assertFalse($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());

        //von
        $header = new Header('19EF00FE');
        $this->assertEquals(126720, $header->getPgn());
        $this->assertFalse($header->isSingelPacked());
        $this->assertTrue($header->isFastPacked());
        $this->assertTrue($header->isPdu1Format());
        $this->assertFalse($header->isPdu2Format());

         //von
       # $header = new Header('19F000FE');
       # $this->assertEquals(126976, $header->getPgn());
       # $this->assertFalse($header->isSingelPacked());
       # $this->assertFalse($header->isFastPacked());
       # $this->assertFalse($header->isPdu1Format());
       # $this->assertTrue($header->isPdu2Format());
        // bis
       # $header = new Header('19FEFFFE');
       # $this->assertEquals(130815, $header->getPgn());
       # $this->assertFalse($header->isSingelPacked());
       # $this->assertFalse($header->isFastPacked());
       # $this->assertFalse($header->isPdu1Format());
       # $this->assertTrue($header->isPdu2Format());

         //von
        $header = new Header('19FF00FE');
        $this->assertEquals(130816, $header->getPgn());
        $this->assertFalse($header->isSingelPacked());
        $this->assertTrue($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());
        // bis
        $header = new Header('19FFFFFE');
        $this->assertEquals(131071, $header->getPgn());
        $this->assertFalse($header->isSingelPacked());
        $this->assertTrue($header->isFastPacked());
        $this->assertFalse($header->isPdu1Format());
        $this->assertTrue($header->isPdu2Format());
    }
}
