<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use NotificationChannels\Messagebird\MessagebirdChannel;
use NotificationChannels\Messagebird\MessagebirdMessage;


class Messagebird extends Otp
{
    protected MessagebirdMessage $messagebirdMessage;

    public function __construct(MessagebirdMessage $messagebirdMessage)
    {
        $this->messagebirdMessage = $messagebirdMessage;
    }

    public function toMessagebird(object $notifiable):MessagebirdMessage
    {
        return $this->messagebirdMessage;
    }

    protected function channel():string
    {
        return MessagebirdChannel::class;
    }

    public function content(string $content):self{
        parent::content($content);
        $this->messagebirdMessage->setBody($content);
        return $this;
    }
}
