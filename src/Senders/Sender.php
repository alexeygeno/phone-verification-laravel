<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp as OtpNotification;

abstract class Sender implements I {

    protected OtpNotification $otpNotification;

    public function __construct(OtpNotification $otpNotification){
        $this->otpNotification = $otpNotification;
    }

    public abstract function invoke(string $to, string $text);
}