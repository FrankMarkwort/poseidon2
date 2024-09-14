<?php
declare(strict_types=1);

namespace Nmea\Cron;

use Modules\Internal\Enums\DebugModeEnum;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Nmea\Cache\CacheInterface;
use Nmea\Database\DatabaseInterface;

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