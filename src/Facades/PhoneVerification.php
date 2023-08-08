<?php

namespace AlexGeno\PhoneVerificationLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \AlexGeno\PhoneVerification\Manager initiate(string $to)
 * @method static \AlexGeno\PhoneVerification\Manager complete(string $to, int $otp)
 */
class PhoneVerification extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \AlexGeno\PhoneVerificationLaravel\PhoneVerification::class;
    }
}
