<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use Illuminate\Support\Facades\Notification;

class Messagebird  extends Sender
{

    public function invoke(string $to, string $text)
    {
        Notification::route('messagebird', $to)->notify($this->otpNotification->content($text));
    }
}
