<?php

use Modules\Internal\Interfaces\InterfaceObservableCronWorker;
use Modules\Internal\RealtimeDistributor;
use Core\Cron\CronWorker;

return [
    RealtimeDistributor::class => function (): RealtimeDistributor {
        return (new RealtimeDistributor())->attach(new Modules\Module\Realtime\Instruments\Bootstrap());
    },
    CronWorker::class => function (InterfaceObservableCronWorker $worker) {
        $worker->attach(new Modules\Module\Cron\AnchorWatch\Bootstrap($worker->isDebug()));
        $worker->attach(new Modules\Module\Cron\WeatherStatistic\Bootstrap());
        $worker->attach(new Modules\Module\Cron\Logbook\Bootstrap());
    }
];
