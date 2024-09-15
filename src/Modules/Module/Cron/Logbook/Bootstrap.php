<?php

namespace Modules\Module\Cron\Logbook;

use Modules\External\FromCache\LogBookFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\Cron\Logbook\Entity\Positions;
use Modules\Module\Cron\Logbook\Mapper\PositionMapper;
use Nmea\Config\ConfigException;
use Nmea\Database\DatabaseInterface;
use Nmea\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private ?PositionMapper $positionMapper = null;
    private ?Positions $position = null;

    /**
     * @throws ConfigException
     * @throws ParserException
     */
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