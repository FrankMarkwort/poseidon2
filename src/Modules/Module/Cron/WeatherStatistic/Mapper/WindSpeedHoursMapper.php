<?php
declare(strict_types=1);

namespace Modules\Module\Cron\WeatherStatistic\Mapper;

use Modules\Module\Cron\WeatherStatistic\Collection\WindSpeedHourCollection;
use Modules\Module\Cron\WeatherStatistic\Entity\WindSpeedCourse;
use Modules\Module\Cron\WeatherStatistic\Entity\WindSpeedHour;
//TODO move to internal
use Core\Database\Mapper\AbstractMapper;

class WindSpeedHoursMapper extends AbstractMapper
{
    public function getAll():WindSpeedHourCollection
    {
        $collection = new WindSpeedHourCollection();
        $result = $this->database->query('select * from wind_speed_hour where `date` BETWEEN NOW() - INTERVAL 2 MONTH AND NOW() order by `date` limit 1000');
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
                ->setMinVesselHeading($row['minVesselHeading'])
                ->setAvgWatertemperature($row['avgWaterTemperature'])
                ->setMaxWatertemperature($row['maxWaterTemperature'])
                ->setMinWatertemperature($row['minWaterTemperature']);
          $collection->addEnity($entity);
        }

        return $collection;
    }

     public function store(WindSpeedCourse $entity):void
     {
        $sqlformat = 'REPLACE INTO wind_speed_minute (`timestamp`, twd, aws, awa, tws, twa, cog, sog, vesselHeading, waterTemperature )'
            . " VALUES ('%s', %s, %s, %s, %s, %s, %s, %s, %s, %s)";
        $sql = vsprintf($sqlformat, $entity->toArray());
        $this->database::getInstance()->execute($sql);
     }
}
