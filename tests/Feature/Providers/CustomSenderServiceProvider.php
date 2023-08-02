<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers;

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
