<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;

class Vonage implements I {

    public function invoke(string $to, string $text)
    {
        Notification::route('vonage', $to)->notify(new \AlexGeno\PhoneVerificationLaravel\Notifications\Vonage($text));
    }
}
