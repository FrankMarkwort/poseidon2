<?php

namespace Nmea\Cron;

use Nmea\Cache\CacheInterface;
use Nmea\Database\DatabaseInterface;
use Nmea\Database\Entity\Positions;
use Nmea\Database\Mapper\PositionMapper;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\Data\DataFacade;

class CronWorker
{
    private bool $running = true;
    private ?Positions $position = null;
    private ?PositionMapper $positionMapper = null;
    public function __construct(private readonly int $sleepTime, private readonly DatabaseInterface $database, private readonly CacheInterface $cache)
    {
    }
    public function run()
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

            return;
        }
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $position = new Positions();
        $position->setLatitude($positionFacade->getFieldValue(1)->getValue())
            ->setLongitude($positionFacade->getFieldValue(2)->getValue());
        if (! $this->isPositionChanged($position)) {

            $this->getPositionMapper()->storeEntity($position);
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

            return;
        }
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $cogSogFacade = DataFacadeFactory::create($cogSogData, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $temperatureFacade = DataFacadeFactory::create($temperature, 'YACHT_DEVICE');
        $mapper = new WindSpeedCourse($this->database);
        #$this->printAllFieldNames($temperatureFacade);
        #var_dump($temperatureFacade->getFieldValue(4)->getValue() - 273.15);
        #return;
        $mapper->setTime($windFacade->getTimestamp())
            ->setApparentWindSpeed( $windFacade->getFieldValue(2)->getValue())
            ->setApparentWindAngle( $windFacade->getFieldValue(3)->getValue())
            ->setCog($cogSogFacade->getFieldValue(4)->getValue())
            ->setSog($cogSogFacade->getFieldValue(5)->getValue())
            ->setVesselHeading($vesselHeadingFacade->getFieldValue(2)->getValue())
            ->setWaterTemperature($temperatureFacade->getFieldValue(4)->getValue());

        $mapper->store();
    }

    private function printAllFieldNames(DataFacade $dataFacade)
    {
        for ($i = 1; $i <= $dataFacade->count(); $i++) {
            echo "$i, " . $dataFacade->getFieldValue($i)->getName() ." ";
            echo "$i, " . $dataFacade->getFieldValue($i)->getValue() ." ";
            echo "$i, " . $dataFacade->getFieldValue($i)->getType() ."\n";
        }
    }
}
