<?php
declare(strict_types=1);

namespace Modules\Module\Cron\WeatherStatistic\Observer;


interface InterfaceObserver
{
    public function update(InterfaceObservable $observable):void;
}