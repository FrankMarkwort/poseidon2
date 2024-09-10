<?php
declare(strict_types=1);

namespace Nmea\Cron;

use Modules\Internal\Enums\DebugModeEnum;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Database\DatabaseInterface;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\Data\DataFacade;

abstract class AbstractCronWorker implements InterfaceObservableCronWorker
{
    private bool $everyMinuteRun;
    protected array $observers;

    abstract function run():void;

     public function __construct(
        protected readonly int $sleepTime,
        protected readonly DatabaseInterface $database,
        protected readonly CacheInterface $cache,
        protected readonly DebugModeEnum $runMode = DebugModeEnum::NORMAL
    ) {}

    public function isDebugRunMode(): bool
    {
        return $this->runMode === DebugModeEnum::DEBUG || $this->runMode === DebugModeEnum::NORMAL_PLUS_DEBUG;
    }


    protected function isDebugPrintMessage(string $message):bool
    {
         if (in_array($this->runMode, [DebugModeEnum::DEBUG, DebugModeEnum::NORMAL_PLUS_DEBUG])) {
                echo $message . PHP_EOL;

                return true;
         }

         return false;
    }

    /**
     * @throws ConfigException
     */
     protected function getPolarVector(DataFacade $dataFacade, int $rFieldValue, int $omegaFieldvalue): PolarVector
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

    public function attach(InterfaceObserverCronWorker $observer):void
    {
        $this->observers[] = $observer;
    }

    public function detach(InterfaceObserverCronWorker $observer):void
    {
        $this->observers = array_diff($this->observers, array($observer));
    }

    public function getCache():CacheInterface
    {
        return $this->cache;
    }

    public function isEveryMinute(): bool
    {
        return $this->everyMinuteRun;
    }

    public function setEveryMinuteRun(bool $value):void
    {
        $this->everyMinuteRun = $value;
    }

     public function notify():void
     {
        foreach ($this->observers as $observer) {
            /**
             * @var $observer InterfaceObserverCronWorker
             */
            $observer->update($this);
        }
    }

    public function getDatabase():DatabaseInterface
    {
        return $this->database;
    }

    public function isDebug():bool
    {
        return $this->runMode === DebugModeEnum::DEBUG || $this->runMode === DebugModeEnum::NORMAL_PLUS_DEBUG;
    }

    public function isNormalRun():bool
    {
        return  $this->runMode === DebugModeEnum::NORMAL || $this->runMode === DebugModeEnum::NORMAL_PLUS_DEBUG;
    }
}