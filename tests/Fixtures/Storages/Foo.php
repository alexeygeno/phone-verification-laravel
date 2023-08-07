<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages;

use AlexGeno\PhoneVerification\Storage\I;

class Foo implements I
{
    /**
     * {@inheritdoc}
     */
    public function sessionUp(string $sessionId, int $otp, int $sessionExpSecs, int $sessionCounterExpSecs): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sessionDown(string $sessionId): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function sessionCounter(string $sessionId): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function otp(string $sessionId): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function otpCheckIncrement(string $sessionId): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function otpCheckCounter(string $sessionId): int
    {
        return 0;
    }
}
