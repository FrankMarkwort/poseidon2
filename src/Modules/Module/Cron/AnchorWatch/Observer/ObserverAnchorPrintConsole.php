<?php
declare(strict_types=1);

namespace Modules\Module\Cron\AnchorWatch\Observer;

use Modules\Module\Cron\AnchorWatch\Anchor;

class ObserverAnchorPrintConsole implements InterfaceObserver
{
    /**
     * @param Anchor $observable
     */
    public function update(InterfaceObservable $observable):void
    {

        if (! $observable->isAnchorSet() && $observable->getChainLength() === 0) {

            return;

        } elseif (! $observable->isAnchorSet() && $observable->getChainLength() > 0) {
            echo  sprintf("try to setAncor if awa +- %s now awa is=%s° chainLength=%sm\n",
                Anchor::WIND_ANGLE_ANCOR_RAD,
                round($observable->getAwaDeg360(),1),
                $observable->getChainLength()
            );
        }
        echo  sprintf("heading=%s°, awa=%s°, circle r=%s, dist=%sm, distInC=%sm, pos(%s, %s) ancPos(%s, %s) %s",
                 intval(rad2deg($observable->getHeadingRad())),
                 intval($observable->getAwaDeg180()),
                 $observable->circleRadiusAnchorBoat(),
                 $observable->getDistance(),
                 $observable->meterInCircle(),
                 rad2deg($observable->getLatitudeRad()),
                 rad2deg($observable->getLongitudeRad()),
                 rad2deg($observable->getAnchorLatitudeRad()),
                 rad2deg($observable->getAnchorLongitudeRad()),
                 $this->getStatus($observable)
        );
    }

    protected function getStatus(InterfaceObservable $observable):string
    {
        if ($observable->isAnchorSet()) {
            if ($observable->hasAlarm() && $observable->hasWarn()) {

                return "\e[31m ANKER ALARM \e[0m \n";

            } elseif ($observable->hasWarn()) {

                return "\e[33m ANKER WARN\e[0m \n";
            }

            return "\e[32m ANKER OK \e[0m \n";
        }

        return  '';
    }
}

