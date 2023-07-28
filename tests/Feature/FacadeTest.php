<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Feature;
use AlexGeno\PhoneVerificationLaravel\Facades\PhoneVerification;

use phpmock\phpunit\PHPMock;

class FacadeTest extends FeatureTestCase
{
    use PHPMock;
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @see https://github.com/php-mock/php-mock-phpunit#restrictions
     * @see https://github.com/orchestral/testbench/issues/371#issuecomment-1649239817
     */
    public function test_process_ok()
    {
        $otp = 1234;
        $to = '+380935258272';

        $rand = $this->getFunctionMock('AlexGeno\PhoneVerification', 'rand');
        $rand->expects($this->once())->willReturn($otp);
        $this->assertEmpty(PhoneVerification::initiate($to));

        $this->assertNotEmpty(PhoneVerification::complete($to, $otp));

    }
}
