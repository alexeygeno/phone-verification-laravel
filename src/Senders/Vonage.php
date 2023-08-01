<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use Illuminate\Support\Facades\Notification;

class Vonage extends Sender
{

    public function invoke(string $to, string $text)
    {
        Notification::route('vonage', $to)->notify($this->otpNotification->content($text));
    }
}
