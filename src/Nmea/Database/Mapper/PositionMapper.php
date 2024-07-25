<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\Entity\Positions;

class PositionMapper extends AbstractMapper
{
    public function storeEntity(Positions $position): false|int
    {
        $sql = 'insert into positions (fid_wind_speed_hour, latitude, longitude, cog, sog, `set`, drift) VALUES (%s , %s , %s, %s, %s ,%s, %s )';
        return $this->getDatabase()->execute(
            sprintf($sql,
            '(SELECT id FROM wind_speed_hour ORDER BY ID DESC LIMIT 1)',
            $position->getLatitude(),
            $position->getLongitude(),
            $this->angleGrad($position->getCourseOverGround()->getOmega()),
            $this->msToKnots($position->getCourseOverGround()->getR()),
            $this->msToKnots($position->getDrift()->getR()),
            $this->angleGrad($position->getDrift()->getOmega()))
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

    private function msToKnots(float $speed):float
    {
        return round($speed * 1.94384 ,1);
    }

     private function angleGrad(float $angle): float
    {
        return round(rad2deg($angle), 0);
    }

}