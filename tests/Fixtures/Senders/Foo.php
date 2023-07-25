<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders;

use AlexGeno\PhoneVerification\Sender\I;

class Foo implements I{

    /**
     * Performs sending
     * Returns API response
     *
     * @param string $to
     * @param string $text
     * @return mixed
     */
    public function invoke(string $to, string $text)
    {

    }
}
