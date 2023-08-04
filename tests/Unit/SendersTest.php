<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class SendersTest extends UnitTestCase
{
    public function drivers()
    {
        return [
            ['messagebird'],
            ['vonage'],
            ['twilio'],
        ];
    }

    /**
     * @dataProvider drivers
     */
    public function test_notification_invocation_ok($driver)
    {
        $text = 'Test text';
        $to = '+15417543010';
        config(['phone-verification.sender.driver' => $driver]);

        Notification::fake();

        app(ISender::class)->invoke($to, $text);

        Notification::assertSentOnDemand(Otp::class,
            function ($notification, array $channels, AnonymousNotifiable $notifiable) use ($text, $to, $driver) {
                $toMethod = 'to'.ucfirst($driver);

                return count($channels) == 1 && current($channels) === $driver &&
                    $notification->$toMethod($notifiable) == $text &&
                    $notification->via($notifiable) == $channels &&
                    $notifiable->routeNotificationFor($driver) == $to;
            }
        );
    }
}
