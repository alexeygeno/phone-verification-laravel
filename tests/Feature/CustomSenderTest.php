<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;

use AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo;
use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Factories\Sender;

class CustomSenderTest extends FeatureTestCase
{
    public function test_custom_sender_available(){
        PhoneVerification::useSenderFactory(Sender::class);
        $this->app->config->set('phone-verification.sender', 'foo');

        $fooSender = $this->app->make(\AlexGeno\PhoneVerification\Sender\I::class);
        $this->assertInstanceOf(Foo::class, $fooSender);
    }
}

