<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Fixtures\Senders;

use AlexGeno\PhoneVerification\Sender\I;

class Foo implements I
{
    /**
     * {@inheritdoc}
     */
    public function invoke(string $to, string $text)
    {
    }
}
