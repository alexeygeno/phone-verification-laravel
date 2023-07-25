<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Factories;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders\Foo;

class Sender extends \AlexGeno\PhoneVerificationLaravel\Factories\Sender
{
    public function foo(){
        return new Foo();
    }
}
