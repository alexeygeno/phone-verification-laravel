<?php

return [
    'storage' => [
        'driver' => env('PHONE_VERIFICATION_STORAGE', 'redis'), // redis || mongodb
        'redis' => [
            'connection' => 'default',
            // the key settings - normally you don't need to change them
            'settings' => [
                'prefix' => 'pv:1',
                'session_key' => 'session',
                'session_counter_key' => 'session_counter',
            ],
        ],
        'mongodb' => [
            'connection' => 'mongodb',
            // the collection settings - normally you don't need to change them
            'settings' => [
                'collection_session' => 'session',
                'collection_session_counter' => 'session_counter',
            ],
        ],
    ],
    'sender' => [
        // vonage || twilio || messagebird and many more https://github.com/laravel-notification-channels
        'channel' => env('PHONE_VERIFICATION_SENDER', 'vonage'),
        'to_log' => false, // if enabled: instead of sending a real notification, debug it to the app log
    ],
    'routes' => true, // managing the availability of the package routes without redefining the service provider
    'manager' => [
        'otp' => ['length' => env('PHONE_VERIFICATION_OTP_LENGTH', 4)],
        'rate_limits' => [
            'initiate' => [ // for every 'to' no more than 10 initiations over 24 hours
                'period_secs' => 86400,
                'count' => 10,
            ],
            'complete' => [ // for every 'to' no more than 5 failed completion over 5 minutes
                'period_secs' => 300, // this is also the expiration period for otp
                'count' => 5,
            ],
        ],
    ],
];
