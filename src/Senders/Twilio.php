<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use Illuminate\Support\Facades\Notification;

class Twilio  extends Sender
{
    public function invoke(string $to, string $text)
    {
        Notification::route('twilio', $to)->notify($this->otpNotification->content($text));
    }
}
