<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;

class CustomStorageServiceProvider extends PhoneVerificationServiceProvider
{
    protected function storages()
    {
        return
            parent::storages() +
            [
                'foo' => [
                    \AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo::class,
                    fn (array $config):array => [ new \StdClass, $config['settings']] // StdClass emulates a client for the Foo storage
                ]
            ];
    }
}
