<?php
declare(strict_types=1);

namespace Modules\Module\Cron\WeatherStatistic\Collection;

use Modules\Module\Cron\WeatherStatistic\Entity\WindSpeedHour;

class WindSpeedHourCollection
{
    /**
     * @var WindSpeedHour[]
     */
    private array $enitiys = [];
    public function addEnity(WindSpeedHour $entity):self
    {
        $this->enitiys[] = $entity;

        return $this;
    }

    public function count():int
    {
        return count($this->enitiys);
    }

    protected function toArray(): array
    {
        $minMax = [];
        $minMaxAws = [];
        $avgAws = [];
        $minMaxAwa = [];
        $avgAwa = [];
        $minMaxTws = [];
        $avgTws = [];
        $minMaxTwa = [];
        $avgTwa = [];
        $minMaxTwd = [];
        $avgTwd = [];
        $minMaxWatertemperature = [];
        $avgWatertemperature = [];
        foreach ($this->enitiys as $entity) {
            $minMaxAws[] = [$entity->getMinAws(),$entity->getMaxAws()];
            $avgAws[] = $entity->getAvgAws();
            $minMaxAwa[] = [$entity->getMinAwa(),$entity->getMaxAwa()];
            $avgAwa[] = $entity->getAvgAwa();
            $minMaxTws[] = [$entity->getMinTws(),$entity->getMaxTws()];
            $avgTws[] = $entity->getAvgTws();
            $minMaxTwa[] = [$entity->getMinTwa(),$entity->getMaxTwa()];
            $avgTwa[] = $entity->getAvgTwa();
            $minMaxTwd[] = [$entity->getMinTwd(),$entity->getMaxTwd()];
            $avgTwd[] = $entity->getAvgTwd();

            $minMaxWatertemperature[] = [$entity->getMinWatertemperature(),$entity->getMaxWatertemperature()];
            $avgWatertemperature[] = $entity->getAvgWatertemperature();
        }
        $startTime = strtotime(reset($this->enitiys)->getDate());
        $endTime = strtotime(end($this->enitiys)->getDate());
        $pointInterval = ($endTime - $startTime) / $this->count();

        return [
            'pointInterval' => $pointInterval * 1000,
            'startTime' => $startTime  * 1000,
            'endTime' => $endTime  * 1000,
            'titleAws' => 'AWS',
            'rangesAws' => $minMaxAws,
            'averagesAws' => $avgAws,
            'titleAwa' => 'AWA',
            'rangesAwa' => $minMaxAwa,
            'averagesAwa' => $avgAwa,
            'titleTws' => 'TWS',
            'rangesTws' => $minMaxTws,
            'averagesTws' => $avgTws,
            'titleTwa' => 'TWA',
            'rangesTwa' => $minMaxTwa,
            'averagesTwa' => $avgTwa,
            'titleTwd' => 'TWD',
            'rangesTwd' => $minMaxTwd,
            'averagesTwd' => $avgTwd,
            'titleWatertemperature' => 'WaterTemperature',
            'rangesWatertemperature' => $minMaxWatertemperature,
            'averagesWatertemperature' => $avgWatertemperature
        ];
    }

    public function toJson():string
    {
        return json_encode($this->toArray());
    }
}