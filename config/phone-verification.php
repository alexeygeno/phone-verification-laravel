<?php
return [
    'storage' => env('PHONE_VERIFICATION_STORAGE', 'redis'),
    'sender' => env('PHONE_VERIFICATION_SENDER', 'vonage'),

    'manager'   => [
        'rate_limits' => [
            'initiate' => [
                'period_secs' => 86400,
                'count' => 1000
            ],
            'complete' => [
                'period_secs' => 300,
                'count' => 5
            ]
        ]
    ]
];
