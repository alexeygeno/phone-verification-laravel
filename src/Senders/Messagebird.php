<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;

use AlexGeno\PhoneVerificationLaravel\Notifications\Messagebird as MessagebirdNotification;


use Illuminate\Support\Facades\Notification;

class Messagebird implements I {

    protected MessagebirdNotification $messagebirdNotification;

    public function __construct(MessagebirdNotification $messageBirdNotification){
        $this->messagebirdNotification = $messageBirdNotification;
    }

    public function invoke(string $to, string $text)
    {
        Notification::route('messagebird', $to)->notify($this->messagebirdNotification->content($text));
    }
}
