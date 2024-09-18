<?php

namespace Modules\External\FromCache;

use Modules\External\AbstractFacade;
use Modules\Internal\Pgns\Heading127250;
use Modules\Internal\Pgns\SogCog129026;
use Modules\Internal\Pgns\Temperature130312;
use Modules\Internal\Pgns\Wind130306;
use Math\Skalar\Rad;
use Math\Vector\PolarVector;

use Core\Cache\CacheInterface;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class WindStatisticFacade extends AbstractFacade
{
    private Heading127250 $heading;
    private Wind130306 $wind;
    private Temperature130312 $temperature;
    private SogCog129026 $sogCog;

    public function  __construct(readonly private CacheInterface $cache, bool $debug = false)
    {
        $this->heading = new Heading127250($this->cache, $debug);
        $this->wind = new Wind130306($this->cache, $debug);
        $this->temperature = new Temperature130312($this->cache, $debug);
        $this->sogCog = new SogCog129026($this->cache, $debug);
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

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getWaterTemperature(): float
    {
        return $this->temperature->getWaterTemperature();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getSog(): float
    {
        return $this->sogCog->getSog();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getCog(): float
    {
        return $this->sogCog->getCog();
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    public function getHeadingVectorRad(): Rad
    {
        return $this->getRad($this->getHeadingRad());
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    public function getCogVector(): PolarVector
    {
        return $this->getPolarVector($this->getSog(), $this->getCog());
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getApparentWindVector(): PolarVector
    {
        return $this->getPolarVector($this->getAws(), $this->getAws());
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getTimestamp():string
    {
        return $this->wind->getTimestamp();
    }
}