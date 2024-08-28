<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\Entity\WindRose;

class WindroseMapper extends AbstractMapper
{

    const float TEST= 22.5;

    private int $count = 0;
    private array $segments = [
        'N', 'NNE', 'NE,', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW' ,'WSW', 'W', 'WNW', 'NW', 'NNW'
    ];

    public function getWindroseEntity()
    {
        $entity = new WindRose();
        $range = 0 - static::TEST / 2;
        $from = $range;
        $to = fmod($range + static::TEST, 360);
        $where = sprintf("(avgTwd between %s and 0) or (avgTwd between 0 and %s)", $from, $to);
        foreach ($this->segments as $segment) {
            $entity->add($segment, $this->getSegment($where, $segment));
            $from = $to;
            $to = $from + static::TEST;
            $where = sprintf("(avgTwd between %s and %s)", $from, $to);
        }

        return $entity;
    }

    private function getSegment(string $where, string $segment)
    {
        $sql = sprintf("select speed_range, count(*) as count from
            (SELECT CASE
            WHEN avgTws <= 1 THEN '0 bf'
            WHEN avgTws > 1  and avgTws <= 3 THEN '1 bf'
            WHEN avgTws > 3  and avgTws <= 6 THEN '2 bf'
            WHEN avgTws > 6  and avgTws <= 10 THEN '3 bf'
            WHEN avgTws > 10 and avgTws <= 15 THEN '4 bf'
            WHEN avgTws > 15 and avgTws <= 21 THEN '5 bf'
            WHEN avgTws > 21 and avgTws <= 27 THEN '6 bf'
            WHEN avgTws > 27 and avgTws <= 33 THEN '7 bf'
            WHEN avgTws > 33 and avgTws <= 40 THEN '8 bf'
            WHEN avgTws > 40 and avgTws <= 47 THEN '9 bf'
            WHEN avgTws > 47 and avgTws <= 55 THEN '10 bf'
            WHEN avgTws > 55 and avgTws <= 63 THEN '11 bf'
            WHEN avgTws  >= 64  THEN '12 bf'
            END as speed_range from wind_speed_hour
            where (%s))
            as wind_summaries group by speed_range", $where);

            $result =  $this->database->query($sql);

            return $result;
    }

    private function countAll()
    {
        $result =  $this->database->query('select count(*) as count from wind_speed_hour' );
    }
}