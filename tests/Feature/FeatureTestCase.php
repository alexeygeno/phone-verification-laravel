<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;


use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;
use AlexGeno\PhoneVerificationLaravel\PhoneVerification;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class FeatureTestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Redis::client()->flushdb();
    }

    protected function getEnvironmentSetUp($app):void
    {
        parent::getEnvironmentSetUp($app);
        $package = $app->make(PhoneVerification::class);
        $package->useRoutes(true);
    }

    protected function getPackageProviders($app):array
    {
        return [
            PhoneVerificationServiceProvider::class,
            RedisMockServiceProvider::class
        ];
    }
}