<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;

use Orchestra\Testbench\TestCase as OrchestraTestCase;


abstract class UnitTestCase extends OrchestraTestCase
{
    protected string $serviceProvider =  PhoneVerificationServiceProvider::class;

    protected function getPackageProviders($app):array
    {
        return [
            $this->serviceProvider,
        ];
    }
}