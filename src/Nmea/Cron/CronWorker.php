<?php

namespace Nmea\Cron;

use Nmea\Cache\CacheInterface;
use Nmea\Database\DatabaseInterface;
use Nmea\Database\Entity\Positions;
use Nmea\Database\Mapper\PositionMapper;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\DataFacadeFactory;

class CronWorker
{
    private bool $running = true;
    private ?Positions $position = null;
    private ?PositionMapper $positionMapper = null;
    private ?string $privTimastamp = null;
    public function __construct(
        private readonly int $sleepTime,
        private readonly DatabaseInterface $database,
        private readonly CacheInterface $cache,
        private readonly ModeEnum $runMode = ModeEnum::NORMAL
    ) {}
    public function run():void
    {
        $i = 0;
        while ($this->running) {
            $i++;
            $this->store(
                $this->cache->get(EnumPgns::WIND->value),
                $this->cache->get(EnumPgns::COG_SOG->value),
                $this->cache->get(EnumPgns::Vessel_Heading->value),
                $this->cache->get(EnumPgns::Temperature->value),
            );
            if ($i >= 60) {
                $i = 0;
                $this->storePosition($this->cache->get(EnumPgns::Position->value));
            }
            sleep($this->sleepTime - date('s') % $this->sleepTime);
        }
    }

    private function storePosition(string $position):void
    {
        if (empty($position)) {
            $this->isDebugPrintMessage('position not changed'.PHP_EOL);

            return;
        }
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $this->isDebugPrintMessage($this->printPositionConsole($positionFacade));
        $position = new Positions();
        $position->setLatitude($positionFacade->getFieldValue(1)->getValue())
            ->setLongitude($positionFacade->getFieldValue(2)->getValue());
        if (! $this->isPositionChanged($position)) {

            $this->getPositionMapper()->storeEntity($position);

            $this->isDebugPrintMessage('store hour position data !' . PHP_EOL);
        }
    }

    private function getPositionMapper():PositionMapper
    {
        if ( ! $this->positionMapper instanceof PositionMapper) {
            $this->positionMapper = new PositionMapper($this->database);
        }

        return $this->positionMapper;
    }

    private function isPositionChanged(Positions $position):bool
    {
        if (! $this->position instanceof Positions) {
            $this->position = $this->getPositionMapper()->fetchLastEntity();

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

    private function store(string $windData, string $cogSogData, string $vesselHeading, string $temperature):void
    {
        if (empty($windData) || empty($cogSogData) || empty($vesselHeading) || empty($temperature)) {
            $this->isDebugPrintMessage('no Data' . PHP_EOL);

            return;
        }
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        if ($this->isPrivTimestampSame($windFacade->getTimestamp())) {
            $this->isDebugPrintMessage('wind timespamp not changed' . $this->privTimastamp . ' = ' .$windFacade->getTimestamp() . PHP_EOL);

            return;
        }
        $this->setPrivTimestamp($windFacade->getTimestamp());
        $cogSogFacade = DataFacadeFactory::create($cogSogData, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $temperatureFacade = DataFacadeFactory::create($temperature, 'YACHT_DEVICE');
        $this->isDebugPrintMessage($this->printWindConsole($temperatureFacade, $windFacade, $cogSogFacade, $vesselHeadingFacade));
        if ($this->runMode == ModeEnum::DEBUG) {

            return;
        }
        if ($this->runMode == ModeEnum::NORMAL || $this->runMode == ModeEnum::NORMAL_PLUS_DEBUG) {
            $mapper = new WindSpeedCourse($this->database);
            $mapper->setTime($windFacade->getTimestamp())
                ->setApparentWind(
                    (new PolarVector())
                        ->setR($windFacade->getFieldValue(2)->getValue())
                        ->setOmega($windFacade->getFieldValue(3)->getValue())
                )
                ->setCourseOverGround(
                    (new PolarVector())
                        ->setR($cogSogFacade->getFieldValue(5)->getValue())
                        ->setOmega($cogSogFacade->getFieldValue(4)->getValue())
                )
                ->setVesselHeading(
                    (new PolarVector())
                        ->setR(0)
                        ->setOmega($vesselHeadingFacade->getFieldValue(2)->getValue())
                )
                ->setWaterTemperature($temperatureFacade->getFieldValue(4)->getValue());

            $mapper->store();
            $this->isDebugPrintMessage('store wind minute data !'. PHP_EOL);
        }
    }

    private function printWindConsole(
        DataFacade $temperatureFacade,
        DataFacade $windFacade,
        DataFacade $cogSogFacade,
        DataFacade $vesselHeadingFacade):string
    {
        return $this->printAllFieldNames($temperatureFacade)
            . $this->printAllFieldNames($windFacade)
            . $this->printAllFieldNames($cogSogFacade)
            . $this->printAllFieldNames($vesselHeadingFacade);

    }

    private function printPositionConsole(DataFacade $position):string
    {
        return $this->printAllFieldNames($position);
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

    private function isDebugPrintMessage(string $message):bool
    {
         if (in_array($this->runMode, [ModeEnum::DEBUG, ModeEnum::NORMAL_PLUS_DEBUG])) {
                echo $message . PHP_EOL;

                return true;
         }

         return false;
    }

    private function printAllFieldNames(DataFacade $dataFacade):string
    {
        $result = $dataFacade->getDescription() . ' pgn => ' . $dataFacade->getPng() . " src => " .$dataFacade->getSrc() . ' dst => ' . $dataFacade->getDst()
            . ' type => ' .  $dataFacade->getFrameType(). ' pduFormat => ' .$dataFacade->getPduFormat() . ' dataPage => ' . $dataFacade->getDataPage() . PHP_EOL;
        for ($i = 1; $i <= $dataFacade->count(); $i++) {
            $result .= "$i, " . $dataFacade->getFieldValue($i)->getName() ." "
                . "$i, " . $dataFacade->getFieldValue($i)->getValue() ." "
                . "$i, " . $dataFacade->getFieldValue($i)->getType() . PHP_EOL;
        }

        return $result;
    }
}
