<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages;

use AlexGeno\PhoneVerification\Storage\I;

class Foo implements I
{
    /**
     * Creates session and increments its counter
     */
    public function sessionUp(string $sessionId, int $otp, int $sessionExpSecs, int $sessionCounterExpSecs): self
    {
    }

    /**
     * Drops session by its id
     */
    public function sessionDown(string $sessionId): self
    {
    }

    /**
     * Returns the amount of recreated sessions
     */
    public function sessionCounter(string $sessionId): int
    {
    }

    /**
     * Returns session otp
     */
    public function otp(string $sessionId): int
    {
    }

    /**
     * Increments the amount of otp checks for the session
     */
    public function otpCheckIncrement(string $sessionId): self
    {
    }

    /**
     * Returns the amount of otp checks for the session
     */
    public function otpCheckCounter(string $sessionId): int
    {
    }
}
