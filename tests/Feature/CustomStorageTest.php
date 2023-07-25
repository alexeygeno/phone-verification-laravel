<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Factories\Storage;

class CustomStorageTest extends FeatureTestCase
{
    public function test_custom_storage_available(){
        PhoneVerification::useStorageFactory(Storage::class);
        $this->app->config->set('phone-verification.storage', 'foo');

        $fooSender = $this->app->make(\AlexGeno\PhoneVerification\Storage\I::class);
        $this->assertInstanceOf(Foo::class, $fooSender);
    }
}

