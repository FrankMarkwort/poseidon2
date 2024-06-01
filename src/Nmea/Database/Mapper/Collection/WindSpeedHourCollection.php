<?php

namespace Nmea\Database\Mapper\Collection;

use Nmea\Database\Mapper\Entity\WindSpeedHour;

class WindSpeedHourCollection
{
    /**
     * @var WindSpeedHour[]
     */
    private $enitiys = [];
    public function addEnity(WindSpeedHour $entity):self
    {
        $this->enitiys[] = $entity;

        return $this;
    }

    public function toArray(): array
    {
        $minMax = [];
        foreach ($this->enitiys as $entity) {
            $minMaxAws[] = [$entity->getMinAws(),$entity->getMaxAws()];
            $avgAws[] = $entity->getAvgAws();
            $minMaxAwa[] = [$entity->getMinAwa(),$entity->getMaxAwa()];
            $avgAwa[] = $entity->getAvgAwa();
        };


        return [ 'rangesAws' => $minMaxAws,
                'averagesAws' => $avgAws,
                'rangesAwa' => $minMaxAwa,
                'averagesAwa' => $avgAwa
        ];
    }

    public function toJson():string
    {
        return json_encode($this->toArray());
    }
}