<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class SendersTest extends UnitTestCase
{
    /**
     * @return array<array<string>>
     */
    public function channels()
    {
        return [
            ['messagebird'],
            ['vonage'],
            ['twilio'],
        ];
    }

    /**
     * Test if notification facade was called properly for different channels
     *
     * @see https://laravel.com/docs/9.x/mocking#on-demand-notifications
     *
     * @dataProvider channels
     *
     * @return void
     */
    public function test_notification_invocation_ok(string $channel)
    {
        $text = 'Test text';
        $to = '+15417543010';
        config(['phone-verification.sender.channel' => $channel]);

        Notification::fake();

        app(ISender::class)->invoke($to, $text);

        Notification::assertSentOnDemand(Otp::class,
            function ($notification, array $channels, AnonymousNotifiable $notifiable) use ($text, $to, $channel) {
                $toMethod = 'to'.ucfirst($channel);

                return count($channels) == 1 && current($channels) === $channel &&
                    $notification->$toMethod($notifiable) == $text &&
                    $notification->via($notifiable) == $channels &&
                    $notifiable->routeNotificationFor($channel) == $to;
            }
        );
    }
}
