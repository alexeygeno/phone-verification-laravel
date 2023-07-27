<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Factories;

use AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages\Foo;

class Storage extends \AlexGeno\PhoneVerificationLaravel\Factories\Storage
{
    public function foo(){
        return new Foo();
    }
}
