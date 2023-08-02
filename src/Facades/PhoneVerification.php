<?php

namespace AlexGeno\PhoneVerificationLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class PhoneVerification extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AlexGeno\PhoneVerificationLaravel\PhoneVerification::class;
    }
}
