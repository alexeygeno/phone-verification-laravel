<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerificationLaravel\Tests\TestCase;

abstract class UnitTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        // Tests will be failed if notifications are not mocked
        $app->config->set('phone-verification.sender.to_log', false);
    }
}
