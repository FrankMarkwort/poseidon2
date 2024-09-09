<?php

namespace Modules\External;

use Modules\Internal\Pgns\Heading127250;
use Modules\Internal\Pgns\SogCog129026;
use Modules\Internal\Pgns\Temperature130312;
use Modules\Internal\Pgns\Wind130306;
use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\ParserException;

class WindStatisticFacade
{
    private Heading127250 $heading;
    private Wind130306 $wind;
    private Temperature130312 $temperature;
    private SogCog129026 $sogCog;

    public function  __construct(private CacheInterface $cache, bool $debug = false)
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

    public function getWaterTemperature(): float
    {
        return $this->temperature->getWaterTemperature();
    }

    public function getSog(): float
    {
        return $this->sogCog->getSog();
    }

    public function getCog(): float
    {
        return $this->sogCog->getCog();
    }

    public function getHeadingVectorRad(): Rad
    {
        return $this->getRad($this->getHeadingRad());
    }

    public function getCogVector(): PolarVector
    {
        return $this->getPolarVector($this->getSog(), $this->getCog());
    }

    public function getApparentWindVector(): PolarVector
    {
        return $this->getPolarVector($this->getAws(), $this->getAws());
    }

    public function getTimestamp():string
    {
        return $this->wind->getTimestamp();
    }

    protected function getPolarVector(float $rFieldValue, float $omegaFieldvalue): PolarVector
    {
         return (new PolarVector())
             ->setR($rFieldValue)
             ->setOmega($omegaFieldvalue);
    }

    protected function getRad(float $rad):Rad
    {
        return (new Rad())->setOmega($rad);
    }
}