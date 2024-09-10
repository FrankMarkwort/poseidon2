<?php

namespace Modules\Module\AnchorWatch;

use Modules\External\AnchorFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\AnchorWatch\Observer\ObserverAnchorPrintConsole;
use Modules\Module\AnchorWatch\Observer\ObserverAnchorToCache;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private Anchor $anchor;

    public function __construct()
    {
        $this->anchor = new Anchor();
        $this->anchor->attach(new ObserverAnchorToCache());
    }

    public function isRunEveryMinute(): bool
    {
        return true;
    }

    public function setDebugMode(): void
    {
         $this->anchor->attach(new ObserverAnchorPrintConsole());
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function update(InterfaceObservableCronWorker $observable): void
    {
        if ($observable->isEveryMinute() === $this->isRunEveryMinute()) {
            $anchorFacade = new AnchorFacade($observable->getCache());
            $this->anchor->setPosition(
                $anchorFacade->getLatitudeDeg(),
                $anchorFacade->getLongitudeDeg(),
                $anchorFacade->getHeadingRad(),
                $anchorFacade->getWaterDepth(),
                $anchorFacade->getAwaRad(),
                $anchorFacade->getAws()
            );
            if (!$anchorFacade->isSetChain()) {
                $this->anchor->unsetAnchor();
                $anchorFacade->removeChainFromCache();
            } else {
                if (!$this->anchor->isAnchorSet()) {
                    $this->anchor->setAnchor($anchorFacade->getChainLength());
                }
            }
        }
    }
}