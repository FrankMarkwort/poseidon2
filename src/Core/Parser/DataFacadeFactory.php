<?php
declare(strict_types=1);

namespace Core\Parser;

use Core\Config\ConfigException;
use Core\Config\ConfigFactory;
use Core\Parser\Data\DataFacade;
use Core\Parser\Data\DataPart;
use Core\Parser\Data\MainPart;
use Core\Parser\Decode\DecodeNmea2000;

class DataFacadeFactory
{
    const string YACHT_DEVICE = 'YACHT_DEVICE';
    const string NONE_DEVICE =  'NON_DEVICE';

    /**
     * @throws ParserException
     * @throws ConfigException
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
