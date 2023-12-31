<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Tests\Feature\Providers\CustomSenderServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo;

class CustomSenderTest extends FeatureTestCase
{
    protected string $serviceProvider = CustomSenderServiceProvider::class;

    /**
     * If a custom sender is resolved properly for the sender interface.
     *
     * @return void
     */
    public function test_custom_sender_available()
    {
        $fooSender = $this->app->make(\AlexGeno\PhoneVerification\Sender\I::class);
        $this->assertInstanceOf(Foo::class, $fooSender);
    }
}
