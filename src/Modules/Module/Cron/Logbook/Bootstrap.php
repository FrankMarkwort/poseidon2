<?php

namespace Modules\Module\Cron\Logbook;

use Modules\External\LogBookFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\Cron\Logbook\Entity\Positions;
use Modules\Module\Cron\Logbook\Mapper\PositionMapper;
use Nmea\Database\DatabaseInterface;

class Bootstrap implements InterfaceObserverCronWorker
{
    private ?PositionMapper $positionMapper = null;
    private ?Positions $position = null;

    public function update(InterfaceObservableCronWorker $observable): void
    {
        $facade = new LogBookFacade($observable->getCache(), $observable->isDebug());
        $position = (new Positions())
            ->setLatitude($facade->getLatitudeDeg())
            ->setLongitude($facade->getLongitudeDeg())
            ->setCourseOverGround($facade->getCourseOverGroundVector())
            ->setDrift($facade->getDriftVector());
        if (! $this->isPositionChanged($observable->getDatabase(), $position)) {
            $this->getPositionMapper($observable->getDatabase())->storeEntity($position);
            $this->printDebugMessage($observable->isDebug(), 'store hour position data !' . PHP_EOL);
        }
    }

    private function getPositionMapper(DatabaseInterface $database):PositionMapper
    {
        if ( ! $this->positionMapper instanceof PositionMapper) {
            $this->positionMapper = new PositionMapper($database);
        }

        return $this->positionMapper;
    }

    private function isPositionChanged(DatabaseInterface $database, Positions $position):bool
    {
        if (! $this->position instanceof Positions) {
            $this->position = $this->getPositionMapper($database)->fetchLastEntity();

            if (! $this->position instanceof Positions) {

                return false;
            }
        }
        if ($this->position->compareTo($position)) {

            return true;
        }
        $this->position = $position;

        return false;

    }

    public function isRunEveryMinute(): bool
    {
        return false;
    }

    protected function printDebugMessage(bool $debug, string $message): void
    {
        if ($debug) {

            echo $message . PHP_EOL;
        }
    }
}