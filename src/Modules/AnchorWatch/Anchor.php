<?php
declare(strict_types=1);

namespace Modules\AnchorWatch;

use Modules\AnchorWatch\Observer\InterfaceObservable;
use Modules\AnchorWatch\Observer\InterfaceObserver;

class Anchor implements InterfaceObservable
{
    private const float EARTH_RADIUS = 6378136.6;
    private const float GPS_BUG_DISTANCE = 11;
    private const int GPS_ACCURACY = 10;
    private const float FREIBOARD = 1;
    public const int WIND_ANGLE_ANCOR_RAD = 5;
    private const int ANCOR_ALARM = 10;
    private const int MAX_HISTORY_POSITIONS = 14000;
    private float|null $previousLatitudeDeg = null;
    private float|null $previousLongitudeDeg = null;
    private float $latitudeRad;
    private float $longitudeRad;
    private float $headingRad;
    private float $aws;
    private float $awaDeg;
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

    public function setPosition(float $latitudeDeg, float $longitudeDeg, float $headingRad, float $waterDepth, float $awaRad, float $aws): self
    {
        $this->isActualHistoryPosition = false;
        $this->aws = $aws;
        $this->awaDeg = rad2deg($awaRad);
        $this->latitudeRad = deg2rad($latitudeDeg);
        $this->longitudeRad = deg2rad($longitudeDeg);
        $this->headingRad = $headingRad;
        $this->setWaterDepth($waterDepth);
        if ($this->isAnchorSet()) {
            $this->addHistoryPoint($latitudeDeg, $longitudeDeg);
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
                if (count($this->historyPosition) > static::MAX_HISTORY_POSITIONS) {
                    array_shift($this->historyPosition);
                }
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
        return $this->awaDeg;
    }

    public function getAwaDeg180():float
    {
        return fmod($this->getAwaDeg360() + 180,360)- 180;
    }

    public function setAnchor(int $chainLength): void
    {
        if (! $this->isAnchorSet()) {
            if ($this->isWindComesFromTheFront()) {
                $this->setChainLength($chainLength);
                $this->setAnchorPosition();
            }
        }
        $this->notify();
    }

    private function isWindComesFromTheFront():bool
    {
        $awa = $this->getAwaDeg360();

        return (($awa >= 0 && $awa <= static::WIND_ANGLE_ANCOR_RAD) || ($awa >= 360 - static::WIND_ANGLE_ANCOR_RAD && $awa <= 360));
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
        $this->isActualHistoryPosition = false;
        $this->chainLength = 0;
        $this->historyPositionRoundCounter = 10;

        return $this;
    }

    public function getAwaRad():float
    {
        return deg2rad($this->awaDeg);
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

    public function getChainLength(): int
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
        return rad2deg($this->getAnchorLongitudeRad());
    }

    protected function setAnchorLongitudeRad(float $anchorLongitudeRad): Anchor
    {
        $this->anchorLongitudeRad = $anchorLongitudeRad;

        return $this;
    }

    public function meterInCircle():int
    {
        return $this->getMaxDistance() - $this->circleRadiusAnchorBoat();
    }

    public function circleRadiusAnchorBoat():int
    {
        return intval(acos((sin($this->getLatitudeRad()) * sin($this->getAnchorLatitudeRad()) + (cos($this->getLatitudeRad()) * cos($this->getAnchorLatitudeRad()))
            * cos($this->getAnchorLongitudeRad() - $this->getLongitudeRad()))) * (static::EARTH_RADIUS) - static::GPS_ACCURACY);
    }

    public function getDistance(): int
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
        $heading = fmod($this->getHeadingRad() + deg2rad($this->getAwaDeg180()), 2 * pi());
        $angularDistance = $this->getDistance() / (static::EARTH_RADIUS);
        $lat2 = asin(sin($this->getLatitudeRad()) * cos($angularDistance) +
            cos($this->getLatitudeRad()) * sin($angularDistance) * cos($heading));
        $lon2 = $this->getLongitudeRad() + atan2(sin($heading) * sin($angularDistance) * cos($this->getLatitudeRad()),
                cos($angularDistance) - sin($this->getLatitudeRad()) * sin($lat2));
        $this->setAnchorLatitudeRad($lat2)->setAnchorLongitudeRad($lon2);
        $this->isSet = true;
    }

    public static function getLine($latitudeRad, $longitudeRad, $angleRad, $length): array
    {
        $distance = $length / static::EARTH_RADIUS;
        $lat2 = asin(sin($latitudeRad) * cos($distance) + cos($latitudeRad) * sin($distance) * cos($angleRad));
        $lon2 = $longitudeRad + atan2(sin($angleRad) * sin($distance) * cos($longitudeRad), cos($distance - sin($latitudeRad) * sin($lat2)));

        return [[rad2deg($longitudeRad), rad2deg($latitudeRad)],[rad2deg($lon2), rad2deg($lat2)]];
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

    private function getLengtBetweenAnchorAndBoat():int
    {
        $lat1 = $this->getLatitudeRad();
        $lat2 = $this->getAnchorLatitudeRad();
        $lon1 = $this->getLongitudeRad();
        $lon2 = $this->getAnchorLongitudeRad();
        $x= ($lat2 - $lat1) * cos(($lon1 + $lon2) / 2);
        $y = ($lon2 - $lon1);

        return intval(sqrt($x * $x + $y * $y) * self::EARTH_RADIUS);
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
            'base' => static::toBaseArray(
                $this->getLatitudeDeg(),
                $this->getLongitudeDeg(),
                $this->getHeadingDeg(),
                $this->getAwaDeg360(),
                $this->aws,
                $this->waterDepth,
                $this->getChainLength(),
                $this->isAnchorSet()
            ),
            'ext' => [
                'anchorLatitude' => $this->getAnchorLatitudeDeg(),
                'anchorLongitude' => $this->getAnchorLongitudeDeg(),
                'anchorLabel' => sprintf("&#9875; &larr;%s&rarr; &#x26F5", $this->getLengtBetweenAnchorAndBoat()),
                'chainLength' => $this->getChainLength(),
                'chainLabel' => sprintf("&#x26D3; &rarr; %sm", $this->getChainLength()),
                'hasAlarm' => $this->hasAlarm(),
                'hasWarn' => $this->hasWarn(),
                'meterInCirle' => $this->meterInCircle(),
                'circleRadius' => $this->circleRadiusAnchorBoat(),
                'distance' => $this->getDistance(),
                'anchorCirclePolygonLabel' => sprintf("&#10807; %sm", $this->getMaxDistance()),
                'anchorColorCirclePolygon' => $this->getStatusColor(),
                'positionsHistory' => $this->getHistoryPositionsWithLastPositionDeg($this->getHistoryPositionsDeg()),
                'anchorCirclePolygon' => [$this->getAnchorCirclePolygonDeg($this->getMaxDistance())],
                'anchorWarnCirclePolygon' => [$this->getAnchorCirclePolygonDeg($this->getMaxDistance() + static::ANCOR_ALARM )],
            ]
        ];
    }

    public static function toBaseArray(float $latDeg, float $lonDeg, float $headingDeg, float $awaDeg, float $aws, float $waterDepth, int $chainLength, bool $isAnchorSet): array
    {
        return [
                'latitude' => $latDeg,
                'longitude' => $lonDeg,
                'boatLabel' => sprintf('&#x26F5 &darr; %sm', round($waterDepth,1)),
                'headingLine' => static::getLine( deg2rad($latDeg), deg2rad($lonDeg), deg2rad($headingDeg) , 12),
                'headingLabel' => sprintf("heading: %s°",intval($headingDeg)),
                'awaLabel' => sprintf('&measuredangle; %s° %s kn', intval(fmod($awaDeg + 180, 360) - 180), intval($aws * 1.943844)),
                'awaLine' => static::getLine( deg2rad($latDeg), deg2rad($lonDeg), deg2rad($awaDeg) + deg2rad($headingDeg), intval($aws * 1.943844)),
                'chainLength' => $chainLength,
                'isSet' => $isAnchorSet,
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