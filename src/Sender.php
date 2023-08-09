<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Support\Facades\Notification;

class Sender implements I
{
    protected Otp $otp;

    protected string $driver;

    protected bool $toLog;

    /**
     * Constructor.
     */
    public function __construct(Otp $otp, string $driver, bool $toLog)
    {
        $this->otp = $otp;
        $this->driver = $driver;
        $this->toLog = $toLog;
    }

    /**
     * Invoke the process of sending.
     *
     * @return void
     */
    public function invoke(string $to, string $text)
    {
        if ($this->toLog) {
            \Illuminate\Support\Facades\Log::info("Pretended notification sending to {$this->driver}:{$to} with message: {$text}");
        } else {
            Notification::route($this->driver, $to)->notify($this->otp->content($text));
        }
    }
}
