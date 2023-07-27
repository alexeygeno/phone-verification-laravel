<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;


class Twilio extends Otp
{
    protected TwilioSmsMessage $twilioSmsMessage;

    public function __construct(TwilioSmsMessage $twilioSmsMessage){
        $this->twilioSmsMessage = $twilioSmsMessage;
    }

    public function toTwilio(object $notifiable):TwilioSmsMessage
    {
        return $this->twilioSmsMessage;
    }

    protected function channel():string
    {
        return TwilioChannel::class;
    }

    public function content(string $content):self{
        parent::content($content);
        $this->twilioSmsMessage->content($content);
        return $this;
    }

}
