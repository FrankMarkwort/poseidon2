<?php

namespace Modules\Module\WeatherStatistic;

use Modules\External\WindStatisticFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private string $privTimastamp;

     /**
     * @throws ParserException
     * @throws ConfigException
     */
     public function update(InterfaceObservableCronWorker $observable):void
     #private function store(string $windData, string $cogSogData, string $vesselHeading, string $temperature):void
     {
        $facade = new WindStatisticFacade($observable->getCache());

        // TODO check all data existe

        if ($this->isPrivTimestampSame($facade->getTimestamp())) {

            return;
        }

        $this->setPrivTimestamp($facade->getTimestamp());
        if ($this->runMode == ModeEnum::DEBUG) {

            return;
        }
        if ($this->runMode == ModeEnum::NORMAL || $this->runMode == ModeEnum::NORMAL_PLUS_DEBUG) {
            $mapper = new WindSpeedCourse($observable->getDatabase());
            $mapper->setTime($facade->getTimestamp())
                ->setApparentWind($facade->getApparentWindVector())
                ->setCourseOverGround($facade->getCogVector())
                ->setVesselHeading($facade->getHeadingVectorRad())
                ->setWaterTemperature($facade->getWaterTemperature());

            $mapper->store();
            $this->isDebugPrintMessage('store wind minute data !'. PHP_EOL);
        }
    }
    
    private function setPrivTimestamp(string $timestamp):void
    {
        $this->privTimastamp = $timestamp;
    }

    private function getPrivTimestamp():string|null
    {
        return $this->privTimastamp;
    }

    private function isPrivTimestampSame(string $timestamp):bool
    {
        return $this->getPrivTimestamp() === $timestamp;
    }

}