<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use NotificationChannels\Messagebird\MessagebirdChannel;
use NotificationChannels\MessageBird\MessagebirdMessage;


class MessageBird extends Otp
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return
     */
    public function toMessagebird($notifiable):MessagebirdMessage
    {
        return (new MessagebirdMessage($this->text));
    }

    protected function channel():string
    {
        return MessagebirdChannel::class;
    }
}
