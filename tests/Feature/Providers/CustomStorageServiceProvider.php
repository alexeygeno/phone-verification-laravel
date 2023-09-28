<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo;

class CustomStorageServiceProvider extends PhoneVerificationServiceProvider
{
    /**
     * Return the Foo storage instance.
     *
     * @param  array<mixed>  $settings ['settings' => [...], 'connection' => string]
     * @return Foo
     */
    protected function fooStorage(array $settings)
    {
        return new Foo();
    }
}
