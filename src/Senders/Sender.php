<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp ;
use Illuminate\Support\Facades\Notification;

abstract class Sender implements I {

    protected Otp $otp;

    public function __construct(Otp $otp){
        $this->otp = $otp;
    }

    public function invoke(string $to, string $text){
        $channel = strtolower((new \ReflectionClass($this))->getShortName());
        Notification::route($channel, $to)->notify($this->otp->content($text));
    }
}