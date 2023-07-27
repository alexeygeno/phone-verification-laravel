<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Providers\CustomSenderServiceProvider;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Factories\Sender;

class CustomSenderTest extends FeatureTestCase
{
    protected string $serviceProvider = CustomSenderServiceProvider::class;

    public function test_custom_sender_available(){
        $this->app->config->set('phone-verification.sender', 'foo');

        $fooSender = $this->app->make(\AlexGeno\PhoneVerification\Sender\I::class);
        $this->assertInstanceOf(Foo::class, $fooSender);
    }
}

