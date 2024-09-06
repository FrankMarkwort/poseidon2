<?php
return [
    'cronWorker' => [
        'AncorWatch' => [
            'class' => function (): Modules\AnchorWatch\Anchor {
                $ancor =  new Modules\AnchorWatch\Anchor;
                $ancor->attach(new Modules\AnchorWatch\Observer\ObserverAnchorToCache);

                return $ancor;
            }
        ]
    ]
];
