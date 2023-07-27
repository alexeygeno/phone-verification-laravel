<?php

namespace AlexGeno\PhoneVerificationLaravel;

class PhoneVerification{

    public function initiate(string $to){
        return app()->make(\AlexGeno\PhoneVerification\Manager\Initiator::class)->initiate($to);
    }

    public function complete(string $to, int $otp){
        return app()->make(\AlexGeno\PhoneVerification\Manager\Completer::class)->complete($to, $otp);
    }
}
