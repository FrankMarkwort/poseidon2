<?php

namespace Modules\External\FromSocket;

use Modules\External\AbstractFacade;
use Core\Config\ConfigException;
use Core\Math\Skalar\Rad;
use Core\Math\Vector\PolarVector;
use Core\Parser\DataFacadeFactory;
use Core\Parser\ParserException;
use Core\Parser\Data\DataFacade;

class InstrumentsFacade extends AbstractFacade
{
    private DataFacade $windFacade;
    private DataFacade $cogSogFacade;
    private DataFacade $vesselHeadingFacade;

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function  __construct(string $windData, string $cogSogData, string $vesselHeading)
    {
        $this->windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $this->cogSogFacade = DataFacadeFactory::create($cogSogData, 'YACHT_DEVICE');
        $this->vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
    }

    /**
     * @throws ConfigException
     */
    public function getHeading(): float
    {
        return $this->vesselHeadingFacade->getFieldValue(2)->getValue();
    }

    /**
     * @throws ConfigException
     */
    public function getAws(): float
    {
        return $this->windFacade->getFieldValue(2)->getValue();
    }

    /**
     * @throws ConfigException
     */
    public function getAwa(): float
    {
        return $this->windFacade->getFieldValue(3)->getValue();
    }

    /**
     * @throws ConfigException
     */
    public function getApparentWindVector():PolarVector
    {
        return $this->getPolarVector($this->getAws(), $this->getAwa());
    }

    /**
     * @throws ConfigException
     */
    public function getSog(): float
    {
        return $this->cogSogFacade->getFieldValue(5)->getValue();
    }

    /**
     * @throws ConfigException
     */
    public function getCog(): float
    {
        return $this->cogSogFacade->getFieldValue(4)->getValue();
    }

    /**
     * @throws ConfigException
     */
    public function getHeadingVectorRad(): Rad
    {
        return $this->getRad($this->getHeading());
    }

    /**
     * @throws ConfigException
     */
    public function getCogVector(): PolarVector
    {
        return $this->getPolarVector($this->getSog(), $this->getCog());
    }

    public function getTimestamp():string
    {
        return $this->cogSogFacade->getTimestamp();
    }
}
