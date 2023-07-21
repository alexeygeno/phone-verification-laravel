<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;

class Twilio implements I {

    public function invoke(string $to, string $text)
    {
        Notification::route('twilio', $to)->notify(new \AlexGeno\PhoneVerificationLaravel\Notifications\Twilio($text));
    }
}
