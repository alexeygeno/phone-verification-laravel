<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;
use AlexGeno\PhoneVerificationLaravel\Notifications\Vonage as VonageNotification;

class Vonage implements I {

    protected VonageNotification $vonageNotification;

    public function __construct(VonageNotification $vonageNotification){
        $this->vonageNotification = $vonageNotification;
    }

    public function invoke(string $to, string $text)
    {
        Notification::route('vonage', $to)->notify($this->vonageNotification->content($text));
    }
}
