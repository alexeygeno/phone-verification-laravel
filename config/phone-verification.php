<?php
return [
    'storage' => env('PHONE_VERIFICATION_STORAGE'),
    'sender' => env('PHONE_VERIFICATION_SENDER'),
    //'manager' => env('PHONE_VERIFICATION_MANAGER')

//    'twilio' => [
//        'account_sid' => env('TWILIO_ACCOUNT_SID'),
//        'auth_token' => env('TWILIO_AUTH_TOKEN'),
//        'from' => env('TWILIO_FROM')
//    ]

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
