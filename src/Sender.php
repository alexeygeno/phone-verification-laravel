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

    /**
     * Sender constructor.
     */
    public function __construct(Otp $otp, string $channel, bool $toLog)
    {
        $this->otp = $otp;
        $this->channel = $channel;
        $this->toLog = $toLog;
    }

    /**
     * Invoke the process of sending.
     *
     * @return mixed|void
     */
    public function invoke(string $to, string $text)
    {
        return
            $this->toLog
            ?
                \Illuminate\Support\Facades\Log::info("Pretended notification sending to {$this->channel}:{$to} with message: {$text}")
            :
                Notification::route($this->channel, $to)->notify($this->otp->content($text));
    }
}
