<?php

return [
    'storage' => [
        'driver' => env('PHONE_VERIFICATION_STORAGE', 'redis'), // redis || mongodb
        'redis' => [
            'prefix' => 'pv:1',
            'session_key' => 'session',
            'session_counter_key' => 'session_counter',
        ],
        'mongodb' => [
            'db' => 'phone_verification',
            'collection_session' => 'session',
            'collection_session_counter' => 'session_counter',
        ],
    ],
    'sender' => [
        'driver' => env('PHONE_VERIFICATION_SENDER', 'vonage'), // vonage || twilio || messagebird
        'to_log' => false, // if enabled: instead of sending a real notification, debug it to the app log
    ],
    'routes' => true, // if the package route is enabled
    'manager' => [
        'otp' => ['length' => env('PHONE_VERIFICATION_OTP_LENGTH', 4)],
        'rate_limits' => [
            'initiate' => [ // for every 'to' no more than 10 initiations over 24 hours
                'period_secs' => 86400,
                'count' => 10,
            ],
            'complete' => [ // for every 'to' no more than 5 failed completion over 5 minutes
                'period_secs' => 300, // this is also the maximum period(since the initiation) to complete
                'count' => 5,
            ],
        ],
    ],
];
