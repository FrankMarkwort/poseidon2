<?php

namespace TestCore\Protocol;

use Core\Cache\ArrayCache;
use Core\Protocol\FramesFactory;
use Modules\Internal\Interfaces\CacheInterface;
use PHPUnit\Framework\TestCase;

class FramesFactoryTest extends TestCase
{
    private FramesFactory $framesFactory;
    private CacheInterface $cache;
    protected function setUp():void
    {
        $this->cache = new ArrayCache();
        FramesFactory::reset();
        FramesFactory::setCache($this->cache);
    }

    public function testAddData()
    {
        FramesFactory::addData('09:44:38.402 R 09F80200 FF FC 20 B4 0F 00 FF FF');
        FramesFactory::addData('09:44:38.402 R 09F80200 FF FC 20 B4 0F 00 FF FF');
        $this->assertEquals('09:44:38.402 R 09F80200 FF FC 20 B4 0F 00 FF FF', $this->cache->get('129026'));
    }
}
