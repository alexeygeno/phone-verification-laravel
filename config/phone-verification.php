<?php
return [
    'storage' => [
        'name' => env('PHONE_VERIFICATION_STORAGE', 'redis'), // redis || mongodb
        'redis' => [
            'prefix' => 'pv:1',
            'session_key' => 'session',
            'session_counter_key' => 'session_counter'
        ],
        'mongodb' => [
            'db' => 'phone_verification',
            'collection_session' => 'session',
            'collection_session_counter' => 'session_counter'
        ]
    ],
    'sender' => env('PHONE_VERIFICATION_SENDER', 'vonage'), // vonage || twilio || messagebird
    'routes' => true, // if the package route is enabled
    'manager'   => [
        'otp' => ['length' => env('PHONE_VERIFICATION_OTP_LENGTH', 4)],
        'rate_limits' => [
            'initiate' => [
                'period_secs' => 86400, // 24 hours
                'count' => 10
            ],
            'complete' => [
                'period_secs' => 300, // 5 minutes
                'count' => 5
            ]
        ]
    ]
];
