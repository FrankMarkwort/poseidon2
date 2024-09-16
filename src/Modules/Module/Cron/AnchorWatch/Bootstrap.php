<?php

namespace Modules\Module\Cron\AnchorWatch;

use Modules\External\FromCache\AnchorFacade;
use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\Interfaces\InterfaceObserverCronWorker;
use Modules\Module\Cron\AnchorWatch\Observer\ObserverAnchorPrintConsole;
use Modules\Module\Cron\AnchorWatch\Observer\ObserverAnchorToCache;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class Bootstrap implements InterfaceObserverCronWorker
{
    private Anchor $anchor;

    public function __construct(bool $isDebug = false)
    {
        $this->anchor = new Anchor();
        $this->anchor->attach(new ObserverAnchorToCache());
        if ($isDebug) {
            $this->anchor->attach(new ObserverAnchorPrintConsole());
        }
    }

    public function isRunEveryMinute(): bool
    {
        return true;
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