<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected string $serviceProvider = PhoneVerificationServiceProvider::class;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        Redis::client()->flushdb();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        // Debug notifications to log instead of sending real ones
        config(['phone-verification.sender.to_log' => true]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [
            $this->serviceProvider,
            RedisMockServiceProvider::class,
        ]);
    }
}
