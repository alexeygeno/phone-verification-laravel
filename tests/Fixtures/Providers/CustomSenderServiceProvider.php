<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Providers;

use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;

class CustomSenderServiceProvider extends PhoneVerificationServiceProvider
{
    protected function senders()
    {
        return
            parent::senders() +
            ['foo' => \AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo::class];
    }
}
