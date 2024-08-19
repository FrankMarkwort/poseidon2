<?php

namespace Nmea\Database\Entity;

use Nmea\Database\Entity\Observer\InterfaceObservable;
use Nmea\Database\Entity\Observer\InterfaceObserver;

class Anchor implements InterfaceObservable
{
    private const float EARTH_RADIUS = 6378136.6;
    private const float GPS_BUG_DISTANCE = 11;
    private const int GPS_ACCURACY = 10;
    private const float FREIBOARD = 1;
    private const int WIND_ANGLE_ANCOR = 5;
    private const int ANCOR_ALARM = 10;

    private float|null $previousLatitudeDeg = null;
    private float|null $previousLongitudeDeg = null;
    private float $latitudeRad;
    private float $longitudeRad;
    private float $headingRad;
    private float $aws;
    private float $awa;
    private bool $isSet = false;
    private float|null $anchorLatitudeRad = null;
    private float|null $anchorLongitudeRad = null;
    private array $historyPosition = [];
    private bool $isActualHistoryPosition = false;
    private int $chainLength = 0;
    private float $waterDepth = 0;
    private int $historyPositionRoundCounter = 10;
    /*
     * @var InterfaceObserver[] $observers
     */
    protected array $observers = [];

    public function setPosition(float $gradLatitude, float $gradLongitude, float $gradHeading, float $waterDepth, float $aws, float $awa): self
    {
        $this->isActualHistoryPosition = false;
        $this->aws = $aws;
        $this->awa = $awa;
        $this->latitudeRad = deg2rad($gradLatitude);
        $this->longitudeRad = deg2rad($gradLongitude);
        $this->headingRad = deg2rad($gradHeading);
        $this->setWaterDepth($waterDepth);
        if ($this->isAnchorSet()) {
            $this->addHistoryPoint($gradLatitude, $gradLongitude);
        }
        $this->notify();

        return $this;
    }

    private function addHistoryPoint(float $latitudeDeg, float $longitudeDeg):void
    {
        $this->isActualHistoryPosition = false;
        $this->historyPositionRoundCounter++;
        if ($this->historyPositionRoundCounter >= 10) {
            if (! (is_null($this->previousLatitudeDeg) || is_null($this->previousLongitudeDeg))) {
                $this->historyPosition[] = [[$this->previousLongitudeDeg, $this->previousLatitudeDeg],[ $longitudeDeg, $latitudeDeg]];
                $this->isActualHistoryPosition = true;
            }
            $this->previousLatitudeDeg = $latitudeDeg;
            $this->previousLongitudeDeg = $longitudeDeg;
            $this->historyPositionRoundCounter = 0;
        }
    }

    private function getHistoryPositionsWithLastPositionDeg(array $historyPositionDeg):array
    {
        if (! (is_null($this->previousLatitudeDeg) || is_null($this->previousLongitudeDeg) || $this->isActualHistoryPosition)) {
            $historyPositionDeg[] = [[$this->previousLongitudeDeg, $this->previousLatitudeDeg],[$this->getAnchorLongitudeDeg(), $this->getLatitudeDeg()]];
        }

        return $historyPositionDeg;
    }

    public function getAwaDeg360():float
    {
        return $this->awa;
    }

    public function setAnchor(float $chainLength): void
    {
        if (! $this->isAnchorSet()) {
            if ($this->isWindComesFromTheFront()) {
                $this->setChainLength($chainLength);
                $this->setAnchorPosition();
            }
        }
    }

    private function isWindComesFromTheFront():bool
    {
        $awa = $this->getAwaDeg360();

        return (($awa >= 0 && $awa <= static::WIND_ANGLE_ANCOR) || ($awa >= 360 - static::WIND_ANGLE_ANCOR && $awa <= 360));
    }

    public function isAnchorSet(): bool
    {
        return $this->isSet;
    }

    public function unsetAnchor(): self
    {
        $this->isSet = false;
        $this->historyPosition = [];
        $this->anchorLatitudeRad= null;
        $this->anchorLongitudeRad = null;
        $this->previousLatitudeDeg = null;
        $this->previousLongitudeDeg = null;
        $this->chainLength = 0;


        return $this;
    }

    public function getLatitudeRad(): float
    {
        return $this->latitudeRad;
    }

    public function getLongitudeRad(): float
    {
        return $this->longitudeRad;
    }

    protected function getLatitudeDeg(): float
    {
        return rad2deg($this->getLatitudeRad());
    }

    protected function getLongitudeDeg(): float
    {
        return rad2deg($this->getLongitudeRad());
    }

    public function getHeadingRad(): float
    {
        return $this->headingRad;
    }

    protected function getHeadingDeg(): float
    {
        return rad2deg($this->getHeadingRad());
    }

    public function getAnchorLatitudeRad(): float
    {
        return $this->anchorLatitudeRad;
    }

    protected function getAnchorLatitudeDeg(): float
    {
        return rad2deg($this->getAnchorLatitudeRad());
    }

    protected function setAnchorLatitudeRad(float $anchorLatitudeRad): self
    {
        $this->anchorLatitudeRad = $anchorLatitudeRad;

        return $this;
    }

    protected function getWaterDepth(): float
    {
        return $this->waterDepth;
    }

    protected function setWaterDepth(float $waterDepth): self
    {
        $this->waterDepth = $waterDepth;

        return $this;
    }

    protected function getChainLength(): int
    {
        return $this->chainLength;
    }

    public function setChainLength(int $chainLength): self
    {
        $this->chainLength = $chainLength;

        return $this;
    }

    public function getAnchorLongitudeRad(): float
    {
        return $this->anchorLongitudeRad;
    }

    public function getAnchorLongitudeDeg(): float
    {
        return rad2deg($this->getLongitudeRad());
    }

    protected function setAnchorLongitudeRad(float $anchorLongitudeRad): Anchor
    {
        $this->anchorLongitudeRad = $anchorLongitudeRad;

        return $this;
    }

    public function meterInCircle():int
    {
        return $this->getMaxDistance() - $this->circleRadius();
    }

    public function circleRadius():int
    {
        return acos((sin($this->getLatitudeRad()) * sin($this->getAnchorLatitudeRad()) + (cos($this->getLatitudeRad()) * cos($this->getAnchorLatitudeRad()))
            * cos($this->getAnchorLongitudeRad() - $this->getLongitudeRad()))) * (static::EARTH_RADIUS) + static::GPS_ACCURACY;
    }

    protected function getDistance(): int
    {
        if ($this->aws <= 5) {
            $faktor = 0;
        } elseif ($this->aws <= 10) {
            $faktor = 0.1;
        } elseif ($this->aws <= 20) {
            $faktor = 0.2;
        } elseif ($this->aws <= 25) {
            $faktor = 0.4;
        } elseif ($this->aws <= 27) {
            $faktor = 0.5;
        } elseif ($this->aws <= 30) {
            $faktor = 0.6;
        } else {
            $faktor = 1.0;
        }

        return intval($this->getMinDistance() + (($this->getMaxDistance() - $this->getMinDistance()) * $faktor));
    }

    public function getMaxDistance():int
    {
        return  intval(sqrt(pow($this->getChainLength(),2) - pow($this->getWaterDepth() + static::FREIBOARD,2)) + static::GPS_BUG_DISTANCE+ static::GPS_ACCURACY);
    }

    protected function getMinDistance():int
    {
        return intval(($this->getChainLength() - ($this->getWaterDepth() + static::FREIBOARD)) + static::GPS_BUG_DISTANCE) + static::GPS_ACCURACY;
    }

    protected function setAnchorPosition() :void
    {
        if ($this->getAwaDeg360() > 180) {
            $awa = 180 - $this->getAwaDeg360() ;
        } else {
            $awa = $this->getAwaDeg360();
        }
        $heading = fmod($this->getHeadingRad() + deg2rad($awa), 2 * pi());
        $angularDistance = $this->getDistance() / (static::EARTH_RADIUS);
        $lat2 = asin(sin($this->getLatitudeRad()) * cos($angularDistance) +
            cos($this->getLatitudeRad()) * sin($angularDistance) * cos($heading));
        $lon2 = $this->getLongitudeRad() + atan2(sin($heading) * sin($angularDistance) * cos($this->getLatitudeRad()),
                cos($angularDistance) - sin($this->getLatitudeRad()) * sin($lat2));

        $this->setAnchorLatitudeRad($lat2)->setAnchorLongitudeRad($lon2);
        $this->isSet = true;
    }

    protected function getAnchorCirclePolygonDeg(float $radius, int $points = 50): array
    {
        if (!$this->isSet) {

            return [];
        }
        $coordinates = [];
         for ($i = 0; $i < $points; $i++) {
            $bearing = 2 * pi() * $i / $points;
            $coordinates[] = $this->getCirclePointDeg($bearing, $radius);
        }
        $coordinates[] = $coordinates[0];

        return $coordinates;

    }

    private function getCirclePointDeg(float $bearing, float $radius): array
    {
        $lon1 = $this->getAnchorLongitudeRad();
        $lat1 = $this->getAnchorLatitudeRad();
        $radiusDivEarthRadius = $radius / static::EARTH_RADIUS;
        $lat = asin(
            sin($lat1) * cos($radiusDivEarthRadius) +
            cos($lat1) * sin($radiusDivEarthRadius) * cos($bearing)
        );
        $lon = $lon1 + atan2(
                sin($bearing) * sin($radiusDivEarthRadius) * cos($lat1),
                cos($radiusDivEarthRadius) - sin($lat1) * sin($lat)
            );
        $lon = fmod(
                $lon + 3 * pi(),
                2 * pi()
            ) - pi();

        return [rad2deg($lon), rad2deg($lat)];
    }

    public function attach(InterfaceObserver $observer):void
    {
        $this->observers[] = $observer;
    }

    public function detach(InterfaceObserver $observer):void
    {
        $this->observers = array_diff($this->observers, array($observer));
    }

    public function notify():void
    {
        foreach ($this->observers as $observer) {
            /**
             * @var $observer InterfaceObserver
             */
            $observer->update($this);
        }
    }

    protected function getHistoryPositionsDeg():array
    {
        return $this->historyPosition;
    }

    protected function toArray():array
    {
        return [
            'latitude' => $this->getLatitudeDeg(),
            'longitude' => $this->getLongitudeDeg(),
            'anchorLatitude' => $this->getAnchorLatitudeDeg(),
            'anchorLongitude' => $this->getAnchorLongitudeDeg(),
            'heading' => $this->getHeadingDeg(),
            'awa' => $this->getAwaDeg360(),
            'aws' => $this->aws,
            'isSet' => $this->isSet,
            'chainLength' => $this->chainLength,
            'waterDepth' => $this->waterDepth,
            'anchorColorCirclePolygon' => $this->getStatusColor(),
            'anchorHistory' => $this->getHistoryPositionsWithLastPositionDeg($this->getHistoryPositionsDeg()),
            'anchorCirclePolygon' => [$this->getAnchorCirclePolygonDeg($this->getMaxDistance())],
            'anchorWarnCirclePolygon' => [$this->getAnchorCirclePolygonDeg($this->getMaxDistance() + static::ANCOR_ALARM )],
            'hasAlarm' => $this->hasAlarm(),
            'hasWarn' => $this->hasWarn(),
            'meterInCirle' => $this->meterInCircle(),
            'circleRadius' => $this->circleRadius(),
            'distance' => $this->getDistance(),
        ];
    }

    public function hasAlarm(): bool
    {
        return $this->meterInCircle() + static::ANCOR_ALARM < 0;
    }

    public function hasWarn(): bool
    {
        return $this->meterInCircle() < 0;
    }

    public function toJson(int $flags = JSON_UNESCAPED_UNICODE):string
    {
        return json_encode($this->toArray(), $flags);
    }

     protected function getStatusColor():string
    {
        if ($this->isAnchorSet()) {
            if ($this->hasAlarm() && $this->hasWarn()) {

                return "rgba(255,0,0,0.3)";

            } elseif ($this->hasWarn()) {

                return "rgba(255,173,0,0.3)";
            }
        }

        return  "rgba(0,255,0,0.3)";
    }
}