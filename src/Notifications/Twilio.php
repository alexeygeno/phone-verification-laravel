<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;


class Twilio extends Otp
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return
     */
    public function toTwilio($notifiable):TwilioSmsMessage
    {
        return (new TwilioSmsMessage())->content($this->text);
    }

    protected function channel():string
    {
        return TwilioChannel::class;
    }

}
