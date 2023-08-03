<?php

namespace AlexGeno\PhoneVerificationLaravel\Senders;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Support\Facades\Notification;

abstract class Sender implements I
{
    protected Otp $otp;

    protected bool $toLog;

    public function __construct(Otp $otp, bool $toLog)
    {
        $this->otp = $otp;
        $this->toLog = $toLog;
    }

    public function invoke(string $to, string $text)
    {
        $channel = strtolower((new \ReflectionClass($this))->getShortName());
        if ($this->toLog) {
            \Illuminate\Support\Facades\Log::info("Pretended notification sending to {$channel}:{$to} with message: {$text}");
        } else {
            Notification::route($channel, $to)->notify($this->otp->content($text));
        }
    }
}
