<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected string $serviceProvider =  PhoneVerificationServiceProvider::class;

    protected function getPackageProviders($app):array
    {
        return [
            $this->serviceProvider
        ];
    }
}