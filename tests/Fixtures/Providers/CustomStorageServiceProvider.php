<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Providers;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;

class CustomStorageServiceProvider extends PhoneVerificationServiceProvider
{
    protected function storages():array{
        return
            parent::storages() +
            ['foo' => [\AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo::class, fn() => new \StdClass]]; // StdClass emulates a client for the Foo storage
    }
}
