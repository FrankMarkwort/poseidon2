<?php

namespace Modules\External;

use Modules\AnchorWatch\Anchor;
use Modules\Internal\Pgns\Chain;
use Modules\Internal\Pgns\Gps129025;
use Modules\Internal\Pgns\Heading127250;
use Modules\Internal\Pgns\WaterDepth128267;
use Modules\Internal\Pgns\Wind130306;
use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class AnchorFacade
{
    private Anchor $anchor;
    private Gps129025 $position;
    private Heading127250 $heading;
    private WaterDepth128267 $waterDepth;
    private Wind130306 $wind;
    private Chain $chain;

    public function  __construct(private CacheInterface $cache)
    {
        $this->position = new Gps129025($this->cache);
        $this->heading = new Heading127250($this->cache);
        $this->waterDepth = new WaterDepth128267($this->cache);
        $this->wind = new Wind130306($this->cache);
        $this->chain = new Chain($this->cache);
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    public function getLatitudeDeg(): float
    {
        return $this->position->getLatitudeDeg();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getLongitudeDeg(): float
    {
        return $this->position->getLongitudeDeg();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getHeadingRad(): float
    {
        return $this->heading->getHeadingRad();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getWaterDepth(): float
    {
        return $this->waterDepth->getWaterDepth();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getAwaRad(): float
    {
        return $this->wind->getAwaRad();
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    public function getAws(): float
    {
        return $this->wind->getAws();
    }

    public function getChainLength(): int
    {
        return $this->chain->getLength();
    }

    public function isSetChain(): bool
    {
        return $this->chain->isSet();
    }

    public function removeChainFromCache():void
    {
        $this->chain->removeFromCache();
    }
}