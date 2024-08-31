<?php
/**
 * @author Frank Markwort
 * @date 13.12.2018
 * @email frank.markwort@gmail.com
 * @project Poseidon
 *
 */
namespace Nmea\Parser\Data;

use Exception;
use Nmea\Parser\Decode\DecodeCanId;
use Nmea\Parser\ParserException;

class MainPart
{
    const string TIMESTAMP = 'timestamp';
    const string PRIO = 'prio';
    const string SRC = 'src';
    const string DST = 'dst';
    const string PGN = 'pgn';
    const string LENGTH = 'length';
    const string DATA_PAGE = 'dataPage';
    const string PDU_FORMAT = 'pduFormat';
    const string GROUP_EXTENSION = 'groupExtension';
    private const string FRAME_TYPE = 'frameType';
    private string $data = '';
    private string $deviceName;
    private array $mainData = [
        self::TIMESTAMP => null,
        self::PRIO => null,
        self::PGN => null,
        self::SRC => null,
        self::DST => null,
        self::LENGTH => null,
        self::DATA_PAGE => null,
        self::PDU_FORMAT => null,
        self::GROUP_EXTENSION => null,
        self::FRAME_TYPE => null,
    ];

    public function __construct(string $deviceType)
    {
        $this->setDeviceType($deviceType);
    }

    public function setDeviceType(string $deviceType):self
    {
        $this->deviceName = $deviceType;

        return $this;
    }

    /**
     * @throws ParserException
     * @throws Exception
     */
    public function setMainBitString(string $string):self
    {
        if ($this->deviceName == 'YACHT_DEVICE') {
            $array = explode( $this->getDelimiter(), $string, 4);
            if (count($array) < 4 ) {

                throw new ParserException('YACHT_DEVICE: main data error => row = ' . $string);
            }
            $this->mainData[static::TIMESTAMP] = $array[0];
            $canDecoder = new DecodeCanId($array[2]);
            $this->mainData[static::PRIO] = $canDecoder->getPriority();
            $this->mainData[static::PGN] =  $canDecoder->getPgn();
            $this->mainData[static::SRC] = $canDecoder->getSourceAdress();
            $this->mainData[static::DATA_PAGE] = $canDecoder->getDataPage();
            $this->mainData[static::PDU_FORMAT] = $canDecoder->getPduFormat();
            $this->mainData[static::GROUP_EXTENSION] = $canDecoder->getGroupExtension();
            $this->mainData[static::DST] = $canDecoder->getDestination();
            if ( $canDecoder->isFastPacked()) {
                $this->mainData[static::FRAME_TYPE] = 'FP';
            } else {
                 $this->mainData[static::FRAME_TYPE] = 'SP';
            }
            $this->data = str_replace(' ', ',', $array[3]);
            $this->mainData[static::LENGTH] = count(explode(',', $this->data));
        } else {
            $array = explode( $this->getDelimiter(), $string, 7);
            if (count($array) < 6 ) {

                throw new ParserException('NO_DEVICE: main data error => row = ' . $string);
            }
            $this->mainData[static::TIMESTAMP] = $array[0];
            $this->mainData[static::PRIO] = $array[1];
            $this->mainData[static::PGN] = $array[2];
            $this->mainData[static::SRC] = $array[3];
            $this->mainData[static::DST] = $array[4];
            $this->mainData[static::LENGTH] = $array[5];
            $this->data = $array[6];
        }

        return $this;
    }

    public function getFrameTye():string
    {
        return $this->mainData[static::FRAME_TYPE];
    }

    public function getData():string
    {
        return $this->data;
    }

    public function getTimestamp():string
    {
        return (string) $this->mainData[static::TIMESTAMP];
    }

     public function getDataPage():?int
    {
        return $this->mainData[static::DATA_PAGE];
    }

    public function getPrio():int
    {
        return (int) $this->mainData[static::PRIO];
    }

    public function getPng():int
    {
        return (int) $this->mainData[static::PGN];
    }

    public function getSrc():int
    {
        return (int) $this->mainData[static::SRC];
    }

    public function getDst():int
    {
        return (int) $this->mainData[static::DST];
    }

    public function getLength():int
    {
        return (int) $this->mainData[static::LENGTH];
    }

    public function getPduFormat():int
    {
        return (int) $this->mainData[static::PDU_FORMAT];
    }

    private function getDelimiter():string
    {
        return match ($this->deviceName) {
            'YACHT_DEVICE' => ' ',
            default => ',',
        };
    }
}
