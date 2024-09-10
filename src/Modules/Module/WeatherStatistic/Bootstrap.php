<?php

namespace Modules\Module\WeatherStatistic;

use Exception;
use Modules\External\WindStatisticFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\WeatherStatistic\Entity\WindSpeedCourse;
//TODO remove from Module
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private string $previousTimestamp = '';

     /**
     * @throws ParserException
     * @throws ConfigException
     */
     public function update(InterfaceObservableCronWorker $observable):void
     {
        try {
            $facade = new WindStatisticFacade($observable->getCache(), $observable->isDebug());
        } catch (Exception) {
            return;
        }
        if ($this->isPreviousTimestampSame($facade->getTimestamp())) {

            return;
        }

        $this->setPreviousTimestamp($facade->getTimestamp());
        if (! $observable->isNormalRun()) {

            return;
        }
        $mapper = new WindSpeedCourse($observable->getDatabase());
        $mapper->setTime($facade->getTimestamp())
            ->setApparentWind($facade->getApparentWindVector())
            ->setCourseOverGround($facade->getCogVector())
            ->setVesselHeading($facade->getHeadingVectorRad())
            ->setWaterTemperature($facade->getWaterTemperature());

        $mapper->store();
            $this->isDebugPrintMessage($observable->isDebug(), 'store wind minute data !');
    }
    
    private function setPreviousTimestamp(string $timestamp):void
    {
        $this->previousTimestamp = $timestamp;
    }

    private function getPreviousTimestamp():string|null
    {
        return $this->previousTimestamp;
    }

    private function isPreviousTimestampSame(string $timestamp):bool
    {
        return $this->getPreviousTimestamp() === $timestamp;
    }

    private function isDebugPrintMessage(bool $debug, string $message):void
    {
        if ($debug) {
            echo $message . PHP_EOL;
        }
    }
}