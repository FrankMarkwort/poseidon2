<?php
declare(strict_types=1);

namespace Nmea\Parser\Data;

use Nmea\Config\ConfigException;
use Nmea\Parser\Decode\DecoderInterface;
use Nmea\Config\PngFieldConfig;
use Nmea\Parser\Decode\Request;

class DataPart
{
    private PngFieldConfig $pngFieldConfig;
    /**
     * @var DecoderInterface
     */
    private DecoderInterface $decoder;

    public function setDecoder(DecoderInterface $decoder):self
    {
        $this->decoder = $decoder;

        return $this;
    }

    public function setData(string $string, int $length, PngFieldConfig $config ):self
    {
        $this->pngFieldConfig = $config;
        $this->decoder->setArray(explode(',', $string) , $length);

        return $this;
    }

    /**
     * @throws ConfigException
     */
    public function getDescription():string
    {
        return $this->pngFieldConfig->getDescription();
    }

    /**
     * @throws ConfigException
     */
    public function getOrderIds():array
    {
        return $this->pngFieldConfig->getOrderIds();
    }

    public function count():int
    {
        return $this->pngFieldConfig->count();
    }

    /**
     * @throws ConfigException
     */
    public function getFieldValue(int $order): Data
    {
        if ( $this->pngFieldConfig->getBitLengthVariable($order) === true) {
            $value = 'TODO BitLengthVariable';
        } else {
            $value = $this->decoder->getValue((new Request())
                ->setType($this->pngFieldConfig->getType($order))
                ->setBitStart($this->pngFieldConfig->getBitStart($order))
                ->setBitLength($this->pngFieldConfig->getBitLength($order))
                ->setBitOffset($this->pngFieldConfig->getBitOffset($order))
                ->setResolution($this->pngFieldConfig->getResolution($order))
                ->setSignet($this->pngFieldConfig->getSigned($order))
                ->setRangeMin($this->pngFieldConfig->getRangeMin($order))
                ->setRangeMax($this->pngFieldConfig->getRangeMax($order))
            );
        }

        return (new Data())
            ->setName($this->pngFieldConfig->getName($order))
            ->setValue($value)
            ->setUnit($this->pngFieldConfig->getUnits($order))
            ->setType($this->pngFieldConfig->getType($order))
            ->setEnum($this->pngFieldConfig->getEnumValues($order));
    }
}
