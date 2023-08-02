<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Providers\CustomStorageServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo;

class CustomStorageTest extends FeatureTestCase
{
    protected string $serviceProvider = CustomStorageServiceProvider::class;

    public function test_custom_storage_available()
    {
        $this->app->config->set('phone-verification.storage',
            [
                'driver' => 'foo',
                'foo' => [
                    'setting1' => 'val1',
                    'setting2' => 'val2',
                ],
            ]
        );

        $fooSender = $this->app->make(\AlexGeno\PhoneVerification\Storage\I::class);
        $this->assertInstanceOf(Foo::class, $fooSender);
    }
}
