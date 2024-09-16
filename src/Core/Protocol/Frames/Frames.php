<?php
declare(strict_types=1);

namespace Core\Protocol\Frames;

use ErrorException;
use Modules\Internal\Enums\EnumPgns;
use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\RealtimeDistributor;
use Modules\Module\Realtime\Instruments\WindSpeedCourseFactory;
use Core\Cache\CacheInterface;
use Core\Config\ConfigException;
use Core\Parser\ParserException;
use Core\Protocol\Frames\Frame\Frame;
use Core\Protocol\Socket\Client;
use Core\Protocol\Socket\SocketException;

class Frames
{
    /*
     * @var Frames[][][]
     */
    private array $frames = [];
    private array $tempStore = [];

    public function __construct(private readonly CacheInterface $cache, private readonly ?Client $webSocket = null, private readonly ?RealtimeDistributor $distributor= null)
    {
    }

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     */
    public function addFrame(Frame $frame):self
    {
        $pgn = $frame->getHeader()->getPgn();
        $sequence = $frame->getSequenceCounter();
        $frameCounter = $frame->getFrameCounter();
        $this->unSetFrameSequenceItIsTheFistFrame($pgn, $sequence, $frameCounter);
        $this->frames[$pgn][$sequence][$frameCounter] = $frame;
        if ($this->isReady($pgn, $sequence)) {
            if ($this->makeData($this->frames[$pgn][$sequence])) {
                unset($this->frames[$pgn][$sequence]);
                if (count($this->frames[$pgn]) === 0) {
                    unset($this->frames[$pgn]);
                }
            }
        }

        return $this;
    }

    private function unSetFrameSequenceItIsTheFistFrame(int $pgn, int $sequence, int $frameCounter):void
    {
        if ($frameCounter === 0 && isset($this->frames[$pgn][$sequence])) {
            unset($this->frames[$pgn][$sequence]);
        }
    }

    private function isReady(int $pgn, int $sequence):bool
    {
        if (isset($this->frames[$pgn][$sequence][0]) && $this->frames[$pgn][$sequence][0] instanceof Frame) {
            if ($this->getFrame($pgn, $sequence, 0)->numberOfFrames() === $this->countFrames($pgn, $sequence)
                || $this->getFrame($pgn, $sequence, 0)->getHeader()->isSingelPacked()) {

                return true;
            }
        }

        return false;
    }

    public function countFrames(int $pgn, int $sequence):int
    {
        return count($this->frames[$pgn][$sequence]);
    }

    private function getFrame(int $pgn, int $sequence, int $framecounter):Frame
    {
        return $this->frames[$pgn][$sequence][$framecounter];
    }

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     */
    private function makeData(array $frames):bool
    {
        if ($frames[0] instanceof Frame) {
            if ($frames[0]->getHeader()->isFastPacked()) {

                return $this->makeFastPackedData($frames);

            } elseif ($frames[0]->getHeader()->isSingelPacked()) {

                return $this->makeSinglePackedData($frames);
            }
        }

        return false;
    }

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     */
    private function addCache(Frame $frame, string $data):void
    {
        $this->cache->set((string) $frame->getHeader()->getPgn(), $frame->getData()->getTimestamp()
                . ' ' . $frame->getData()->getDirection() . ' ' . $frame->getHeader()->getCanIdHex() . ' ' . $data);
        try {
            if ($this->distributor instanceof InterfaceObservableRealtime) {
                $this->distributor->setFrame($frame, $data, $this->webSocket);
            }
        } catch (SocketException) {}
    }

    //TODO implement sequence

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     */
    private function makeSinglePackedData(array $frames, ?RealtimeDistributor $distributor = null):bool
    {
        if ($frames[0] instanceof Frame) {

            $data = '';
            $splitter = '';
            foreach ($frames[0]->getData()->getDataBytes() as $index => $byte) {
                    $data .= $splitter . $byte ;
                    $splitter = ' ';
            }
            $this->addCache($frames[0], $data);

            return true;
        }

        return false;
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     * @throws ErrorException
     */
    private function makeFastPackedData(array $frames):bool
    {
        $data = '';
        $splitter = '';
        foreach ($frames as $frameCounter => $frame) {
            if ($frame instanceof Frame) {
                if ($frameCounter === 0) {
                    foreach ($frame->getData()->getDataBytes() as $index => $byte) {
                        if ($index < 2 ) continue;
                            $data .= $splitter . $byte;
                            $splitter = ' ';
                    }
                } else {
                    foreach ($frame->getData()->getDataBytes() as $index => $byte) {
                        if ($index < 1 ) continue;
                            $data .= $splitter . $byte;
                            $splitter = ' ';
                    }
                }
            }
        }
        if ($data !== '' && $frames[0] instanceof Frame) {
            $this->addCache($frames[0], $data);

                return true;
        }

        return false;
    }
}