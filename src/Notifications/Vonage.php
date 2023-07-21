<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\VonageMessage;


class Vonage extends Otp
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return
     */
    public function toVonage($notifiable):VonageMessage
    {
        return (new VonageMessage())->content($this->text);
    }

    protected function channel():string
    {
        return VonageSmsChannel::class;
    }

}
