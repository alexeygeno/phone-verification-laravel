<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Support\Facades\Notification;

class Sender implements I
{
    protected Otp $otp;

    protected string $channel;

    protected bool $toLog;

    public function __construct(Otp $otp, string $channel, bool $toLog)
    {
        $this->otp = $otp;
        $this->channel = $channel;
        $this->toLog = $toLog;
    }

    public function invoke(string $to, string $text)
    {
        if ($this->toLog) {
            \Illuminate\Support\Facades\Log::info("Pretended notification sending to {$this->channel}:{$to} with message: {$text}");
        } else {
            Notification::route($this->channel, $to)->notify($this->otp->content($text));
        }
    }
}
