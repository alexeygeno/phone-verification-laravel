<?php

namespace AlexGeno\PhoneVerificationLaravel;

class PhoneVerification
{
    public function initiate(string $to)
    {
        return app(\AlexGeno\PhoneVerification\Manager\Initiator::class)->initiate($to);
    }

    public function complete(string $to, int $otp)
    {
        return app(\AlexGeno\PhoneVerification\Manager\Completer::class)->complete($to, $otp);
    }
}
