<?php
declare(strict_types=1);

namespace Modules\Module\Realtime\Instruments;

use Modules\External\FromSocket\InstrumentsFacade;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;
use Nmea\Protocol\Socket\Client;
use Nmea\Protocol\Socket\SocketException;

readonly class WindSpeedCourseFactory
{
    private const string ROOM = 'testmsg';
    public function __construct(private ?Client $webSocket = null)
    {
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     * @throws SocketException
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
