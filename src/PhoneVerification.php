<?php

namespace AlexGeno\PhoneVerificationLaravel;

class PhoneVerification
{
    /**
     * Initiate verification process.
     *
     * @return \AlexGeno\PhoneVerification\Manager\Initiator
     */
    public function initiate(string $to)
    {
        return app(\AlexGeno\PhoneVerification\Manager\Initiator::class)->initiate($to);
    }

    /**
     * Complete verification process.
     *
     * @return \AlexGeno\PhoneVerification\Manager\Completer
     */
    public function complete(string $to, int $otp)
    {
        return app(\AlexGeno\PhoneVerification\Manager\Completer::class)->complete($to, $otp);
    }
}
