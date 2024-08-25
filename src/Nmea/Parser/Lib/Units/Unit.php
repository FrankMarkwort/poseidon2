<?php

namespace Nmea\Parser\Lib\Units;

use DateTimeImmutable;
use DateInterval;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;

class Unit implements UnitInterface
{
    private const string GRAD = 'grad';
    private const string MIN = 'min';
    private const string SEC = 'sec';
    private const string DIRECTION = 'direction';
    private const string CONFIG_DIR_FILE = __DIR__ . '/../../../config/units.php';
    private array $unitsConfig;
    private array $supported = ['km/h', 'kt', 'C', 'F', 'hPa', 'grad'];

    public function __construct(private float|int $value, private readonly string|null $unit, private ?string $type= null)
    {
        $this->setConfig(include( static::CONFIG_DIR_FILE));
    }

    public function getMappedValue(): float|int
    {
        return $this->convert();
    }

    public function setConfig(array $unitsConfig): void
    {
        $this->unitsConfig = $unitsConfig;
    }

    public function getMappedUnit(): string
    {
        if (isset($this->unitsConfig[$this->unit]) && $this->isSupported($this->unitsConfig[$this->unit][static::UNIT])) {

            return $this->unitsConfig[$this->unit][static::UNIT];
        }

        return $this->unit;
    }
    private function isSupported(?string $unit):bool
    {
        return in_array($unit, $this->supported);
    }
    public function getMappedValueWithUnit():string
    {
        switch ($this->unit) {
            case 'deg':
                $deg = $this->latitudeOrLongitude($this->type);
                return sprintf($this->unitsConfig[$this->unit][static::PRINT], $deg[static::GRAD], $deg[static::MIN], $deg[static::SEC],$deg[static::DIRECTION]);
            case static::SI_GES_DAY:
                return sprintf($this->unitsConfig[$this->unit][static::PRINT],$this->date());
            case static::SI_SECONDS:
                return sprintf($this->unitsConfig[$this->unit][static::PRINT],$this->time());
            case static::SI_METRE:
                if ($this->value >= 1000) {
                    return sprintf($this->unitsConfig[$this->unit][static::PRINT],$this->convert(), 'Km');
                }
                return sprintf($this->unitsConfig[$this->unit][static::PRINT],$this->convert(), static::SI_METRE);
            case static::SI_SPEED:
            case static::SI_ANGEL:
            case static::SI_TEMPERATURE:
            case static::SI_PASCAL:
            default:
               if ($this->isSupported($this->unitsConfig[$this->unit][static::UNIT])) {
                   return sprintf($this->unitsConfig[$this->unit][static::PRINT], $this->getMappedValue());
               }
        }
        return sprintf("%s %s", $this->getMappedValue(), $this->unit);
    }

    private function convert(): float|int
    {
        return match ($this->unit) {
            static::SI_SPEED => $this->speed(),
            static::SI_ANGEL => $this->angle(),
            static::SI_TEMPERATURE => $this->temperature(),
            static::SI_PASCAL => $this->pressure(),
            static::SI_METRE => $this->metre(),
            default => $this->value,
        };
    }

    private function metre(): float|int
    {
        if ($this->value >= 1000) {

            return $this->value/1000;
        }

        return $this->value;
    }

    private function angle():float|int
    {
        if ($this->unitsConfig[$this->unit][static::UNIT] == static::GRAD) {

            return round(rad2deg($this->value),$this->unitsConfig[$this->unit][static::ROUND]);
        }

        return $this->value;
    }

    private function date():string
    {
        $days = round($this->getMappedValue());
        return (new DateTimeImmutable())->setDate(1970,1,1)->add(new DateInterval("P{$days}D"))->format('Y-m-d');
    }

    private function time():string
    {
        return gmdate("H:i:s", round($this->getMappedValue()));
    }

    private function speed():float|int
    {
        return match ($this->unitsConfig[$this->unit][static::UNIT]) {
            'km/h' => round($this->value * 3.6, $this->unitsConfig[$this->unit][static::ROUND]),
            'kt' => round($this->value * 1.9438444924574, $this->unitsConfig[$this->unit][static::ROUND]),
            default => $this->value,
        };
    }

    private function temperature():float|int
    {
        return match ($this->unitsConfig[$this->unit][static::UNIT]) {
            'C' => round($this->value - 273.15, $this->unitsConfig[$this->unit][static::ROUND]),
            'F' => round(($this->value - 273.15) * (9 / 5) + 32, $this->unitsConfig[$this->unit][static::ROUND]),
            default => $this->value,
        };
    }

    private function latitudeOrLongitude(string $type):array
    {
        $grad[static::DIRECTION] = $this->getDirectionForlatitudeOrLongitude(intval($this->value), $type);
        $grad[static::GRAD] = intval(abs($this->value));
        $x = (abs($this->value) - $grad[static::GRAD]) * 60;
        $grad[static::MIN] = intval($x);
        $grad[static::SEC] = round( ($x - $grad[static::MIN]) * 60 , 2);
        if ($this->type === 'Latitude' && abs($this->value) !== 0) {
            $grad[static::GRAD] = str_pad($grad[static::GRAD],3, 0, STR_PAD_LEFT);
        }

        return $grad;
    }

    private function getDirectionForlatitudeOrLongitude(int $grad, string $type):string
    {
        if ($type == 'Latitude') {
            if  ($grad > 0) {

               return 'N';

            } elseif($grad < 0) {

                return 'S';
            }
        } elseif ($type == 'Longitude') {
            if  ($grad > 0) {

                return 'E';

            } elseif($grad < 0) {

                return 'W';
            }
        }

        return '';
    }

    private function pressure():float|int
    {
        return match ($this->unitsConfig[$this->unit][static::UNIT]) {
            'hPa' => round($this->value / 100, $this->unitsConfig[$this->unit][static::ROUND]),
            default => $this->value,
        };
    }
}