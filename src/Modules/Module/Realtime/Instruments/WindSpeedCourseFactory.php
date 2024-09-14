<?php
declare(strict_types=1);

namespace Modules\Module\Realtime\Instruments;

use ErrorException;
use Modules\External\FromSocket\InstrumentsFacade;
use Nmea\Config\ConfigException;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\ParserException;
use Nmea\Protocol\Socket\Client;

readonly class WindSpeedCourseFactory
{
    private const string ROOM = 'testmsg';
    public function __construct(private ?Client $webSocket = null)
    {
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     * @throws ErrorException
     */
    public function writeToSocket(string $windData, string $cogSogData, string $vesselHeading):void
     {
        if ($this->webSocket === null) {

            return;
        }
        if (empty($windData) || empty($cogSogData) || empty($vesselHeading)) {

            return;
        }
        $facade = new InstrumentsFacade($windData, $cogSogData, $vesselHeading);
        $windSpeedCourse = new WindSpeedCourse();
        $windSpeedCourse->setApparentWind($facade->getApparentWindVector())
            ->setCourseOverGround($facade->getCogVector())
            ->setVesselHeading($facade->getHeadingVectorRad());
        $this->webSocket->send(json_encode([static::ROOM => $windSpeedCourse->toArray()]));
    }
}
