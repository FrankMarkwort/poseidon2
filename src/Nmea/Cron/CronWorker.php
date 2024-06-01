<?php

namespace Nmea\Cron;

use Nmea\Cache\CacheInterface;
use Nmea\Database\DatabaseInterface;
use Nmea\Database\Mapper\WindSpeedCourse;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\Data\DataFacade;

class CronWorker
{
    private bool $running = true;
    public function __construct(private readonly int $sleepTime, private readonly DatabaseInterface $database, private readonly CacheInterface $cache)
    {
    }
    public function run()
    {
        while ($this->running) {
           $this->store(
               $this->cache->get(EnumPgns::WIND->value),
               $this->cache->get(EnumPgns::COG_SOG->value),
               $this->cache->get(EnumPgns::Vessel_Heading->value),
           );
           sleep($this->sleepTime - date('s') % $this->sleepTime);
        }
    }

    public function stop():void
    {
        $this->running = false;
    }

    public function store(string $windData, string $cogSogData, string $vesselHeading):void
    {
        if (empty($windData) || empty($cogSogData) || empty($vesselHeading)) {

            return;
        }

        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $cogSogFacade = DataFacadeFactory::create($cogSogData, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $mapper = new WindSpeedCourse($this->database);
        $mapper->setTime($windFacade->getTimestamp())
            ->setApparentWindSpeed( $windFacade->getFieldValue(2)->getValue())
            ->setApparentWindAngle( $windFacade->getFieldValue(3)->getValue())
            ->setCog($cogSogFacade->getFieldValue(4)->getValue())
            ->setSog($cogSogFacade->getFieldValue(5)->getValue())
            ->setVesselHeading($vesselHeadingFacade->getFieldValue(2)->getValue());

        $mapper->store();
    }

    private function printAllFieldNames(DataFacade $dataFacade)
    {
        for ($i = 1; $i < $dataFacade->count(); $i++) {
            var_dump ($i, $dataFacade->getFieldValue($i)->getName());
            var_dump ($i, $dataFacade->getFieldValue($i)->getValue());
            var_dump ($i, $dataFacade->getFieldValue($i)->getType());
        }
    }
}
