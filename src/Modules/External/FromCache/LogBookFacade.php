<?php

namespace Modules\External\FromCache;

use Modules\External\AbstractFacade;
use Modules\Internal\Pgns\Gps129025;
use Modules\Internal\Pgns\SetAndDrift129291;
use Modules\Internal\Pgns\SogCog129026;
use Core\Cache\CacheInterface;
use Core\Config\ConfigException;
use Core\Math\Vector\PolarVector;
use Core\Parser\ParserException;

class LogBookFacade extends AbstractFacade
{
    private Gps129025 $position;
    private SetAndDrift129291 $setAndDrift;
    private SogCog129026 $sogCog;

    public function __construct(CacheInterface $cache, bool $debug = false)
    {
        $this->position = new Gps129025($cache, $debug);
        $this->setAndDrift = new SetAndDrift129291($cache, $debug );
        $this->sogCog = new SogCog129026($cache, $debug);
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getLatitudeDeg():float
    {
        return $this->position->getLatitudeDeg();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getLongitudeDeg():float
    {
        return $this->position->getLongitudeDeg();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getDrift():float
    {
        return $this->setAndDrift->getDrift();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getSet():float
    {
        return $this->setAndDrift->getSet();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getSog():float
    {
        return $this->sogCog->getSog();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getCog():float
    {
        return $this->sogCog->getCog();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getDriftVector():PolarVector
    {
        return $this->getPolarVector($this->getDrift(), $this->getSet());
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getCourseOverGroundVector():PolarVector
    {
        return $this->getPolarVector($this->getSog(), $this->getCog());
    }

}