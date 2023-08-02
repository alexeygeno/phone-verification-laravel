<?php

namespace AlexGeno\PhoneVerificationLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed initiate(string $to)
 * @method static \AlexGeno\PhoneVerification\Manager complete(string $to, int $otp)
 */
class PhoneVerification extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AlexGeno\PhoneVerificationLaravel\PhoneVerification::class;
    }
}
