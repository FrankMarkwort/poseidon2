<?php

namespace Modules;

use Nmea\Cron\EnumPgns;
use Nmea\Parser\DataFacadeFactory;

class Values
{
    public function getLatitude()
    {
        $position = $this->cache->get(EnumPgns::POSITION->value);
        $vesselHeading = $this->cache->get(EnumPgns::VESSEL_HEADING->value);
        $waterDepth = $this->cache->get(EnumPgns::WATER_DEPTH->value);
        $windData = $this->cache->get(EnumPgns::WIND->value);

        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $waterDepthFacade = DataFacadeFactory::create($waterDepth, 'YACHT_DEVICE');
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
    }

    public function getLongitude()
    {
    }
}