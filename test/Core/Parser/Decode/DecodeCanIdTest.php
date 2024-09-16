<?php

namespace TestCore\Parser\Decode;

use Core\Protocol\Frames\Frame\Header\PackedTypeHelper;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Core\Parser\Decode\DecodeCanId;

#[CoversClass(DecodeCanId::class)]
#[CoversClass(PackedTypeHelper::class)]
final class DecodeCanIdTest extends TestCase
{
    /**
     * @var DecodeCanId
     */
    private $decodeCanId;

    public function setUp(): void
    {
        $this->decodeCanId = new DecodeCanId('0CF004FE');
    }

    public function testGetDecCanId()
    {
        $this->assertEquals(217056510, $this->decodeCanId->getCanIdDec());
    }

    public function testGetDBC()
    {
        $this->assertEquals(0 , $this->decodeCanId->getCanExtendedId());
    }

    public function testGetPriority()
    {
        $this->assertEquals(3 , $this->decodeCanId->getPriority());
    }

    public function testGetReserved()
    {
         $this->assertEquals(0 , $this->decodeCanId->getReserved());
    }

    public function testGetDp()
    {
        $this->assertEquals(0 , $this->decodeCanId->getDataPage());
    }

    public function testGetPf()
    {
         $this->assertEquals(240 , $this->decodeCanId->getPduFormat());
    }

    public function testGetPS()
    {
         $this->assertEquals(4 , $this->decodeCanId->getPduSpecific());
    }

    public function testGetSa()
    {
        $this->assertEquals(254 , $this->decodeCanId->getSourceAdress());
    }

    public function testGetPgn()
    {
        $this->assertEquals(61444 , $this->decodeCanId->getPgn());
    }

    public function testDF50BEE()
    {
        $test = new DecodeCanId('DF50BEE');
        $this->assertEquals(128267, $test->getPgn());

    }

}
