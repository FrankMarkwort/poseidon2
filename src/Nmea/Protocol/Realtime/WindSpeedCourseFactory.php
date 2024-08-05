<?php

namespace Nmea\Protocol\Realtime;

use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Protocol\Socket\Client;

class WindSpeedCourseFactory
{
    private $pgn = null;

    public function __construct(private Client $webSocket)
    {
    }

     public function writeToSocket(string $windData, string $cogSogData, string $vesselHeading):void
     {
        if (empty($windData) || empty($cogSogData) || empty($vesselHeading)) {
            //wait
            return;
        }
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $cogSogFacade = DataFacadeFactory::create($cogSogData, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $windSpeedCourse = new WindSpeedCourse();
        $windSpeedCourse->setApparentWind($this->getPolarVector($windFacade,2,3))
            ->setCourseOverGround($this->getPolarVector($cogSogFacade,5,4))
            ->setVesselHeading($this->getNewRad($vesselHeadingFacade->getFieldValue(2)->getValue()));
        $this->webSocket->send(json_encode(['testmsg' => $windSpeedCourse->toArray()]));
    }

    protected function getPolarVector(DataFacade $dataFacade, int $rFieldValue, float $omegaFieldvalue): PolarVector
    {
         return (new PolarVector())
             ->setR($dataFacade->getFieldValue($rFieldValue)->getValue())
             ->setOmega($dataFacade->getFieldValue($omegaFieldvalue)->getValue()
         );
    }

    protected function getNewRad(float $rad):Rad
    {
        return (new Rad())->setOmega($rad);
    }
}