<?php

namespace Nmea\Database\Entity;

use Nmea\Database\Entity\Observer\InterfaceObservable;
use Nmea\Database\Entity\Observer\InterfaceObserver;

class Anchor implements InterfaceObservable
{
    private const EARTH_RADIUS = 6378136.6;
    private const float GPS_BUG_DISTANCE = 11;
    private const int GPS_ACCURACY = 10;
    private const float FREIBOARD = 1;
    private const int WIND_ANGLE_ANCOR = 5;
    private const int ANCOR_ALARM = 10;
    private float $latitude;
    private float $longitude;
    private float $heading;
    private float $aws;
    private float $awa;
    private bool $isSet = false;
    private float|null $anchorLatitude;
    private float|null $anchorLongitude;
    private array $historyPosition = [];
    private int $chainLength = 0;
    private float $waterDepth = 5;
    private int $historyPositionCounter = 10;
    /*
     * @var InterfaceObserver[] $observers
     */
    protected array $observers = [];

    public function setPosition(float $gradLatitude, float $gradLongitude, float $gradHeading, float $waterDepth, float $aws, float $awa): self
    {
        $this->aws = $aws;
        $this->awa = $awa;
        $this->latitude = deg2rad($gradLatitude);
        $this->longitude = deg2rad($gradLongitude);
        $this->heading = deg2rad($gradHeading);
        $this->setWaterDepth($waterDepth);
        if ($this->isAnchorSet()) {
            $this->addHistoryPoint($gradLongitude, $gradLatitude);
        }
        $this->notify();

        return $this;
    }

    private function addHistoryPoint(float $latitude, float $longitude):void
    {
        $this->historyPositionCounter++;
        if ($this->historyPositionCounter >= 10) {
            $this->historyPosition[] = [$latitude, $longitude];
            $this->historyPositionCounter = 0;
        }
    }

    public function getAwa():float
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
        return (($this->awa >= 0 && $this->awa <= static::WIND_ANGLE_ANCOR) || ($this->awa >= 360 - static::WIND_ANGLE_ANCOR && $this->awa <= 360));
    }

    public function isAnchorSet(): bool
    {
        return $this->isSet;
    }

    public function unsetAnchor(): self
    {
        $this->isSet = false;
        $this->historyPosition = [];
        $this->anchorLatitude= null;
        $this->anchorLongitude = null;


        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getHeading(): float
    {
        return $this->heading;
    }

    public function getAnchorLatitude(): float
    {
        return $this->anchorLatitude;
    }

    protected function setAnchorLatitude(float $anchorLatitude): self
    {
        $this->anchorLatitude = $anchorLatitude;

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

    public function getAnchorLongitude(): float
    {
        return $this->anchorLongitude;
    }

    protected function setAnchorLongitude(float $anchorLongitude): Anchor
    {
        $this->anchorLongitude = $anchorLongitude;

        return $this;
    }

    public function meterInCircle():int
    {
        return intval($this->circleRadius() - $this->getMaxDistance());
    }

    public function isInCircle():bool
    {
        if ($this->isSet) {

            return $this->circleRadius() <= $this->getMaxDistance();
        }

        return true;
    }

    public function circleRadius():int
    {
        return intval(acos((sin($this->getLatitude()) * sin($this->getAnchorLatitude()) + (cos($this->getLatitude()) * cos($this->getAnchorLatitude()))
            * cos($this->getAnchorLongitude() - $this->getLongitude()))) * (static::EARTH_RADIUS)) + static::GPS_ACCURACY;
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
        return  intval(sqrt(pow($this->getChainLength(),2) - pow($this->getWaterDepth() + static::FREIBOARD,2)) + static::GPS_BUG_DISTANCE);
    }

    protected function getMinDistance():int
    {
        return intval(($this->getChainLength() - ($this->getWaterDepth() + static::FREIBOARD)) + static::GPS_BUG_DISTANCE);
    }

    protected function setAnchorPosition() :void
    {
        $angularDistance = $this->getDistance() / (static::EARTH_RADIUS);
        $lat2 = asin(sin($this->getLatitude()) * cos($angularDistance) +
            cos($this->getLatitude()) * sin($angularDistance) * cos($this->getHeading()));
        $lon2 = $this->getLongitude() + atan2(sin($this->getHeading()) * sin($angularDistance) * cos($this->getLatitude()),
                cos($angularDistance) - sin($this->getLatitude()) * sin($lat2));

        $this->setAnchorLatitude($lat2)->setAnchorLongitude($lon2);
        $this->isSet = true;
    }

    protected function getAnchorCirclePolygon(float $radius, int $points = 50): array
    {
        if (!$this->isSet) {

            return [];
        }
         for ($i = 0; $i < $points; $i++) {
            $bearing = 2 * pi() * $i / $points;
            $coordinates[] = $this->getCirclePoint($bearing, $radius);
        }

        $coordinates[] = $coordinates[0];

        return $coordinates;

    }

    private function getCirclePoint(float $bearing, float $radius): array
    {
        $lon1 = $this->getAnchorLongitude();
        $lat1 = $this->getAnchorLatitude();
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

    public function attach(InterfaceObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(InterfaceObserver $observer)
    {
        $this->observers = array_diff($this->observers, array($observer));
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            /**
             * @var $observer InterfaceObserver
             */
            $observer->update($this);
        }
    }

    public function toArray():array
    {
        return [
            'latitude' => rad2deg($this->latitude),
            'longitude' => rad2deg($this->longitude),
            'anchorLatitude' => rad2deg($this->anchorLatitude),
            'anchorLongitude' => rad2deg($this->anchorLongitude),
            'heading' => rad2deg($this->heading),
            'distance' => $this->getDistance(),
            'awa' => $this->awa,
            'aws' => $this->aws,
            'isSet' => $this->isSet,
            'chainLength' => $this->chainLength,
            'waterDepth' => $this->waterDepth,
            'anchorColorCirclePolygon' => $this->getStatusColor(),
            'anchorHistory' => [$this->historyPosition],
            'anchorCirclePolygon' => [$this->getAnchorCirclePolygon($this->getMaxDistance())],
            'anchorWarnCirclePolygon' => [$this->getAnchorCirclePolygon($this->getMaxDistance() + static::ANCOR_ALARM )],
            'hasAlarm' => $this->meterInCircle() >= static::ANCOR_ALARM
        ];
    }

    public function toJson(int $flags = JSON_UNESCAPED_UNICODE):string
    {
        return json_encode($this->toArray(), $flags);
    }

     protected function getStatusColor():string
    {
        if ($this->isAnchorSet()) {
            if ($this->meterInCircle() <= 0.00) {
                return "rgba(0,255,0,0.3)";
            } elseif ($this->meterInCircle() >= static::ANCOR_ALARM) {
                return "rgba(255,0,0,0.3)";
            } else {
                return "rgba(252,224,1,0.3)";
            }
        } else {
            return  "rgba(45,45,45,0.3)";
        }
    }
}