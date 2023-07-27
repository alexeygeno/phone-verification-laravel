<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Manager\Completer;
use AlexGeno\PhoneVerification\Manager\Initiator;

class PhoneVerification{

    public function initiate(string $to){
        return app()->make(Initiator::class)->initiate($to);
    }

    public function complete(string $to, int $otp){
        return app()->make(Completer::class)->initiate($to, $otp);
    }
}
