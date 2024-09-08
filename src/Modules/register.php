<?php
return [
    'cronWorker' => [
        'AncorWatch' => [
            'class' => function (): \Modules\Module\AnchorWatch\Anchor {
                $ancor =  new \Modules\Module\AnchorWatch\Anchor;
                $ancor->attach(new \Modules\Module\AnchorWatch\Observer\ObserverAnchorToCache);

                return $ancor;
            }
        ]
    ]
];
