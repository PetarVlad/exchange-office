<?php

return [
    'recipient' => env('NOTIFICATIONS_RECIPIENT'),
    'order' => [
        'currency_iso' => explode(',', env('NOTIFICATIONS_ORDER_CURRENCY_ISOS', 'GBP')),
    ],
];
