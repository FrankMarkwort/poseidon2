<?php
return [
    'cronWorker' => [
        'AncorWatch' => [
            'class' => function (): \Modules\Module\Cron\AnchorWatch\Anchor {
                $ancor =  new \Modules\Module\Cron\AnchorWatch\Anchor;
                $ancor->attach(new \Modules\Module\Cron\AnchorWatch\Observer\ObserverAnchorToCache);

                return $ancor;
            }
        ]
    ]
];
