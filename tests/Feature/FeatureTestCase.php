<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

use PhoneVerification;


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
        PhoneVerification::useRoutes(true);
    }

    protected function getPackageProviders($app):array
    {
        return [
            PhoneVerificationServiceProvider::class,
            RedisMockServiceProvider::class
        ];
    }
    protected function getPackageAliases($app):array
    {
        return [
            'PhoneVerification' => 'AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification'
        ];
    }

}