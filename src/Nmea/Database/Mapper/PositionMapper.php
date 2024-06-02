<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\Entity\Positions;

class PositionMapper extends AbstractMapper
{
    public function storeEntity(Positions $position): false|int
    {
        $sql = 'insert into positions (fid_wind_speed_hour, latitude, longitude) VALUES (%s , %s , %s )';
        return $this->getDatabase()->execute(
            sprintf($sql,
            '(SELECT id FROM wind_speed_hour ORDER BY ID DESC LIMIT 1)',
            $position->getLatitude(),
            $position->getLongitude())
        );
    }

    public function fetchLastEntity():Positions|null
    {
        $sql = 'SELECT latitude, longitude FROM positions order by id desc limit 1';
        $result = $this->getDatabase()->query($sql);
        if(empty($result)) {

            return null;
        }

        return (new Positions())->setLatitude($result[0]["latitude"])->setLongitude($result[0]["longitude"]);

    }

}