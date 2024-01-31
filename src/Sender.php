<?php

namespace AlexGeno\PhoneVerificationLaravel;

use AlexGeno\PhoneVerification\Sender\I;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Notifications\AnonymousNotifiable;
use Psr\Log\LoggerInterface;

class Sender implements I
{
    /**
     * Constructor.
     */
    public function __construct(
        protected Otp $otp,
        protected AnonymousNotifiable $notifiable,
        protected LoggerInterface $logger,
        protected string $driver,
        protected bool $toLog
    ) {
    }

    /**
     * Invoke the process of sending.
     *
     * @return void
     */
    public function invoke(string $to, string $text)
    {
        if ($this->toLog) {
            $this->logger->info("Pretended notification sending to {$this->driver}:{$to} with message: {$text}");
        } else {
            $this->notifiable->route($this->driver, $to)->notify($this->otp->content($text));
        }
    }
}
