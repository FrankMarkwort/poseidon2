<?php
/**
 * @author Frank Markwort
 * @date 13.12.2018
 * @email frank.markwort@gmail.com
 * @project Poseidon
 *
 */
namespace Nmea\Parser\Data;

use Nmea\Parser\Lib\Units;

class Data
{
    private const string LOOKUP_TABLE = 'Lookup table';
    private const string MANUFACTURER_CODE = 'Manufacturer code';
    private int|float|string|null $value;
    private ?string $name;
    private ?string $unit;
    private ?array $enum;
    private ?string $type;

    public function getValueWhithUnit() : ?string
    {
        if (is_null($this->getUnit()) || strlen($this->getUnit()) === 0) {

            return $this->getValue();
        }
        return $this->getValue() . ' ' . $this->getUnit();

    }

    public function getValue():mixed
    {
        if (!empty($this->enum) && ($this->getType() === static::LOOKUP_TABLE || $this->getType() === static::MANUFACTURER_CODE )) {
            return $this->findEnum(intval($this->value));
        }

        return $this->value;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getUnit():?string
    {
        return $this->unit;
    }

    private function getEnum():?array
    {
        return $this->enum;
    }

    public function setValue(mixed $value):self
    {
        $this->value = $value;

        return $this;
    }

    public function setName(string $name):self
    {
        $this->name = $name;

        return $this;
    }

    public function setUnit(?string $unit):self
    {
        $this->unit = $unit;

        return $this;
    }

    public function setEnum($enum):self
    {
        $this->enum = $enum;

        return $this;
    }

    public function getType():?string
    {
        return $this->type;
    }

    public function setType(?string $type):self
    {
        $this->type = $type;

        return $this;
    }

    private function findEnum($enun)
    {

        $results = array_filter($this->enum, function($enun) {

            return $enun['value'] == $this->value;
        });

        $value =  current($results);
        if (empty($value)) {
            return '';
        }

        return $value['name'];
    }
}
