<?php

namespace AlexGeno\PhoneVerificationLaravel\Factories;

use AlexGeno\PhoneVerification\Sender\I as ISender;

class Sender {
    public function twilio():ISender{
        return new \AlexGeno\PhoneVerificationLaravel\Senders\Twilio();
    }

    public function messageBird():ISender{
        return new \AlexGeno\PhoneVerificationLaravel\Senders\MessageBird();
    }

    public function vonage():ISender{
        return new \AlexGeno\PhoneVerificationLaravel\Senders\Vonage();
    }
}
