<?php


namespace Nmea\Parser;

use Nmea\Config\ConfigFactory;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\Data\DataPart;
use Nmea\Parser\Data\MainPart;
use Nmea\Parser\Decode\DecodeNmea2000;

class DataFacadeFactory
{
    const string YACHT_DEVICE = 'YACHT_DEVICE';
    const string NONE_DEVICE =  'NON_DEVICE';

    /**
     * @throws ParserException
     */
    public static function create(string $nmea2000Data, string $device = self::NONE_DEVICE):DataFacade
    {

        $mainPart = static::getNewMainPartInstance($device)->setMainBitString($nmea2000Data);
        $pngFieldConfig = ConfigFactory::create($mainPart->getPng());
        $dataPart = static::getNewDataPartInstance()->setData(
            $mainPart->getData(),
            $mainPart->getLength(),
            $pngFieldConfig
        );

        return new DataFacade($mainPart, $dataPart);
    }

    private static function getNewMainPartInstance(string $device):MainPart
    {
        return new MainPart($device);
    }

    private static function getNewDataPartInstance():DataPart
    {
        return (new DataPart())->setDecoder(DecodeNmea2000::getInstance());
    }
}
