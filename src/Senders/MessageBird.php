<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;

class MessageBird implements I {

    public function invoke(string $to, string $text)
    {
        Notification::route('messagebird', $to)->notify(new \AlexGeno\PhoneVerificationLaravel\Notifications\MessageBird($text));
    }
}
