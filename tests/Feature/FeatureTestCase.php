<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Tests\Unit\UnitTestCase;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
//use NotificationChannels\Messagebird\MessagebirdServiceProvider;
//use NotificationChannels\Twilio\TwilioProvider;
//use Illuminate\Notifications\VonageChannelServiceProvider;


abstract class FeatureTestCase extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Redis::client()->flushdb();
    }

//    protected function getEnvironmentSetUp($app):void
//    {
//        parent::getEnvironmentSetUp($app);
//        $app->config->set('services.messagebird',
//            [
//             'access_key' => env('MESSAGEBIRD_ACCESS_KEY'),
//             'originator' =>  env('MESSAGEBIRD_ORIGINATOR'),
//             'recipients' => env('MESSAGEBIRD_RECIPIENTS')
//            ]
//        );
//    }

    protected function getPackageProviders($app):array
    {
        return array_merge(parent::getPackageProviders($app), [
            RedisMockServiceProvider::class,
//            VonageChannelServiceProvider::class,
//            MessagebirdServiceProvider::class,
//            TwilioProvider::class
        ]);
    }
}