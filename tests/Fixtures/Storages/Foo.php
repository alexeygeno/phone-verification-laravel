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
        return $this;
    }

    /**
     * Drops session by its id
     */
    public function sessionDown(string $sessionId): self
    {
        return $this;
    }

    /**
     * Returns the amount of recreated sessions
     */
    public function sessionCounter(string $sessionId): int
    {
        return 0;
    }

    /**
     * Returns session otp
     */
    public function otp(string $sessionId): int
    {
        return 0;
    }

    /**
     * Increments the amount of otp checks for the session
     */
    public function otpCheckIncrement(string $sessionId): self
    {
        return $this;
    }

    /**
     * Returns the amount of otp checks for the session
     */
    public function otpCheckCounter(string $sessionId): int
    {
        return 0;
    }
}
