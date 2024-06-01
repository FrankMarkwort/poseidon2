<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\DatabaseInterface;
use Nmea\Database\Mapper\Collection\WindSpeedHourCollection;
use Nmea\Database\Mapper\Entity\WindSpeedHour;

class WindSpeedHoursMapper
{
    public function __construct(private readonly DatabaseInterface $database)
    {
    }

    public function getAll():WindSpeedHourCollection
    {
        $collection = new WindSpeedHourCollection();
        $result = $this->database->query('select * from wind_speed_hour');
        foreach ($result as $row) {
            $entity = new WindSpeedHour();
            $entity->setDate($row['date'])
                ->setAvgTwd($row['avgTwd'])
                ->setMaxTwd($row['maxTwd'])
                ->setMinTwd($row['minTwd'])
                ->setAvgAws($row['avgAws'])
                ->setMaxAws($row['maxAws'])
                ->setMinAws($row['minAws'])
                ->setAvgAwa($row['avgAwa'])
                ->setMaxAwa($row['maxAwa'])
                ->setMinAwa($row['minAwa'])
                ->setAvgTws($row['avgTws'])
                ->setMaxTws($row['maxTws'])
                ->setMinTws($row['minTws'])
                ->setAvgTwa($row['avgTwa'])
                ->setMaxTwa($row['maxTwa'])
                ->setMinTwa($row['minTwa'])
                ->setAvgCog($row['avgCog'])
                ->setMaxCog($row['maxCog'])
                ->setMinCog($row['minCog'])
                ->setAvgSog($row['avgSog'])
                ->setMaxSog($row['maxSog'])
                ->setMinSog($row['minSog'])
                ->setAvgVesselHeading($row['avgVesselHeading'])
                ->setMaxVesselHeading($row['maxVesselHeading'])
                ->setMinVesselHeading($row['minVesselHeading']);
          $collection->addEnity($entity);
        }

        return $collection;
    }
}
