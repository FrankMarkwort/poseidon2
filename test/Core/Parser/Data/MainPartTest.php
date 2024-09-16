<?php
namespace Core\Parser\Data;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MainPart::class)]
class MainPartTest extends TestCase
{
    /**
     * @var MainPart
     */
    private $mainPart;

    protected function setUp():void
    {
        $this->mainPart =new MainPart('');
    }

    public function testSetAndGet()
    {
        $testData = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $this->mainPart->setMainBitString($testData);
        $this->assertEquals('2011-11-24-22:42:04.388', $this->mainPart->getTimestamp());
        $this->assertEquals(2, $this->mainPart->getPrio());
        $this->assertEquals(127251, $this->mainPart->getPng());
        $this->assertEquals(36, $this->mainPart->getSrc());
        $this->assertEquals(255, $this->mainPart->getDst());
        $this->assertEquals(8, $this->mainPart->getLength());
        $this->assertEquals('7d,0b,7d,02,00,ff,ff,ff', $this->mainPart->getData());
    }

    public function testGetDataPage()
    {
        $testData = '2011-11-24-22:42:04.388,2,127251,36,255,8,7d,0b,7d,02,00,ff,ff,ff';
        $this->mainPart->setMainBitString($testData);
        $this->assertEquals(null, $this->mainPart->getDataPage() );
    }

    public function testException()
    {
        $this->expectException(Exception::class);
        $testData = '2011-11-24-22:42:04.388,2,127251,36,255';
        $this->mainPart->setMainBitString($testData);
    }
}