<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use Illuminate\Support\Facades\Notification;

use AlexGeno\PhoneVerificationLaravel\Notifications\Twilio as TwilioNotification;

class Twilio implements I {

    protected TwilioNotification $twilioNotification;

    public function __construct(TwilioNotification $twilioNotification){
        $this->twilioNotification = $twilioNotification;
    }

    public function invoke(string $to, string $text)
    {
        Notification::route('twilio', $to)->notify($this->twilioNotification->content($text));
    }
}
