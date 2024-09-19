<?php

namespace Modules\Module\Cron\WeatherStatistic;

use Exception;
use Modules\External\FromCache\WindStatisticFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\Cron\WeatherStatistic\Entity\WindSpeedCourse;
use Modules\Module\Cron\WeatherStatistic\Mapper\WindSpeedHoursMapper;
//TODO catch it in CORE
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private string $previousTimestamp = '';

    /**
     * @throws ConfigException
     * @throws ParserException
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
        (new WindSpeedHoursMapper($observable->getDatabase()))->store(
            (new WindSpeedCourse())
            ->setTime($facade->getTimestamp())
            ->setApparentWind($facade->getApparentWindVector())
            ->setCourseOverGround($facade->getCogVector())
            ->setVesselHeading($facade->getHeadingVectorRad())
            ->setWaterTemperature($facade->getWaterTemperature())
        );
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

    public function isRunEveryMinute(): bool
    {
        return true;
    }
}