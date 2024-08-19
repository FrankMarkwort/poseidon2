<?php

namespace Nmea\Cron;

use Nmea\Database\Entity\Anchor;
use Nmea\Database\Entity\Observer\ObserverAnchor;
use Nmea\Database\Entity\Observer\ObserverAnchorToCache;
use Nmea\Database\Entity\Positions;
use Nmea\Database\Mapper\PositionMapper;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\ParserException;

final class CronWorker extends AbstractCronWorker
{
    private const string CHAIN_LENGTH = 'chain_length';

    private bool $running = true;
    private ?Positions $position = null;
    private ?PositionMapper $positionMapper = null;
    private ?string $privTimastamp = null;
    private ?Anchor $anchor = null;
    public function run():void
    {
        $this->anchor = new Anchor();
        $this->anchor->attach(new ObserverAnchorToCache());
        if ($this->isDebugRunMode()) {
            $this->anchor->attach(new ObserverAnchor());
        }
        $i = 0;
        while ($this->running) {
            $i++;
            $this->anchor(
                $this->cache->get(EnumPgns::Position->value),
                $this->cache->get(EnumPgns::Vessel_Heading->value),
                $this->cache->get(EnumPgns::Water_Depth->value),
                $this->cache->get(EnumPgns::WIND->value)
            );
            $this->store(
                $this->cache->get(EnumPgns::WIND->value),
                $this->cache->get(EnumPgns::COG_SOG->value),
                $this->cache->get(EnumPgns::Vessel_Heading->value),
                $this->cache->get(EnumPgns::Temperature->value),
            );
            if ($i >= 60) {
                $i = 0;
                $this->storePosition(
                    $this->cache->get(EnumPgns::Position->value),
                    $this->cache->get(EnumPgns::COG_SOG->value),
                    $this->cache->get(EnumPgns::Set_And_Drift->value)
                );
            }
            sleep($this->sleepTime - date('s') % $this->sleepTime);
        }
    }

    /**
     * @throws ParserException
     */
    private function anchor(string $position, string $vesselHeading, string $waterDepth, string $windData): void
    {
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $waterDepthFacade = DataFacadeFactory::create($waterDepth, 'YACHT_DEVICE');
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $this->anchor->setPosition(
            $positionFacade->getFieldValue(1)->getValue(),
            $positionFacade->getFieldValue(2)->getValue(),
            $vesselHeadingFacade->getFieldValue(2)->getValue(),
            ($waterDepthFacade->getFieldValue(2)->getValue() + $waterDepthFacade->getFieldValue(3)->getValue()),
            $windFacade->getFieldValue(3)->getValue(),
            $windFacade->getFieldValue(2)->getValue()
        );
        if (! $this->cache->isSet(static::CHAIN_LENGTH)) {
            $this->anchor->unsetAnchor();
        } else {
            if (! $this->anchor->isAnchorSet()) {
                $this->anchor->setAnchor($this->cache->get(static::CHAIN_LENGTH));
            }
        }
    }

    private function storePosition(string $position, string $courseOverGround, $drift):void
    {
        if (empty($position) || empty($courseOverGround) || empty($drift)) {
            $this->isDebugPrintMessage('invalid data store position'.PHP_EOL);

            return;
        }
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $courseFacade = DataFacadeFactory::create($courseOverGround, 'YACHT_DEVICE');
        $driftFacade = DataFacadeFactory::create($drift, 'YACHT_DEVICE');
        $this->isDebugPrintMessage($this->printPositionConsole($positionFacade, $courseFacade, $driftFacade));
        $position = new Positions();
        $position->setLatitude($positionFacade->getFieldValue(1)->getValue())
            ->setLongitude($positionFacade->getFieldValue(2)->getValue())
            ->setCourseOverGround($this->getPolarVector($courseFacade,5,4 ))
            ->setDrift($this->getPolarVector($driftFacade,5,4 ));
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
                ->setApparentWind($this->getPolarVector($windFacade,2,3))
                ->setCourseOverGround($this->getPolarVector($cogSogFacade,5,4))
                ->setVesselHeading($this->getNewRad($vesselHeadingFacade->getFieldValue(2)->getValue()))
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

    private function printPositionConsole(DataFacade $position, DataFacade $courseOberGround, DataFacade $drift):string
    {
        return $this->printAllFieldNames($position)
            . $this->printAllFieldNames($courseOberGround)
            . $this->printAllFieldNames($drift);
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
