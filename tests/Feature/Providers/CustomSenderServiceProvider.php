<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers;

use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerificationLaravel\PhoneVerificationServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo;

class CustomSenderServiceProvider extends PhoneVerificationServiceProvider
{
    protected function registerSender()
    {
        $this->app->bind(ISender::class, function ($container) {
            return $container->make(Foo::class);
        });
    }
}
