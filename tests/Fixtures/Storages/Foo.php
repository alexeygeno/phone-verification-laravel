<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Storages;

use AlexGeno\PhoneVerification\Storage\I;

class Foo implements I
{

    /**
     * Creates session and increments its counter
     *
     * @param string  $sessionId
     * @param integer $otp
     * @param integer $sessionExpSecs
     * @param integer $sessionCounterExpSecs
     * @return Foo
     */
    public function sessionUp(string $sessionId, int $otp, int $sessionExpSecs, int $sessionCounterExpSecs): self{

    }

    /**
     * Drops session by its id
     *
     * @param string $sessionId
     * @return Foo
     */
    public function sessionDown(string $sessionId): self{

    }

    /**
     * Returns the amount of recreated sessions
     *
     * @param string $sessionId
     * @return integer
     */
    public function sessionCounter(string $sessionId): int{

    }

    /**
     * Returns session otp
     *
     * @param string $sessionId
     * @return integer
     */
    public function otp(string $sessionId): int{

    }

    /**
     * Increments the amount of otp checks for the session
     *
     * @param string $sessionId
     * @return Foo
     */
    public function otpCheckIncrement(string $sessionId): self{

    }

    /**
     * Returns the amount of otp checks for the session
     *
     * @param string $sessionId
     * @return integer
     */
    public function otpCheckCounter(string $sessionId): int{

    }
}
