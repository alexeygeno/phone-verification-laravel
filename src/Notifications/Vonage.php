<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use Illuminate\Notifications\Channels\VonageSmsChannel;
use Illuminate\Notifications\Messages\VonageMessage;


class Vonage extends Otp
{
    protected VonageMessage $vonageMessage;

    public function __construct(VonageMessage $vonageMessage)
    {
        $this->vonageMessage = $vonageMessage;
    }

    public function toVonage(object $notifiable):VonageMessage
    {
        return $this->vonageMessage;
    }

    protected function channel():string
    {
        return VonageSmsChannel::class;
    }

    public function content(string $content):self{
        parent::content($content);
        $this->vonageMessage->content($content);
        return $this;
    }
}
