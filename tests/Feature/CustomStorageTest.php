<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers\CustomStorageServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo;

class CustomStorageTest extends FeatureTestCase
{
    protected string $serviceProvider = CustomStorageServiceProvider::class;

    /**
     * If a custom storage is resolved properly for the storage interface.
     *
     * @return void
     */
    public function test_custom_storage_available()
    {
        config(
            ['phone-verification.storage' => [
                'driver' => 'foo',
                'foo' => [
                    'connection' => 'foo_connection',
                    'settings' => [
                        'setting1' => 'val1',
                        'setting2' => 'val2',
                    ],
                ],
            ],
            ]
        );

        $fooStorage = $this->app->make(\AlexGeno\PhoneVerification\Storage\I::class);
        $this->assertInstanceOf(Foo::class, $fooStorage);
    }
}
