<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerification\Sender\I as ISender;
use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

class SendersTest extends UnitTestCase
{
    /**
     * @return array<int, array<int, string>>
     */
    public function channels()
    {
        return [
            ['messagebird', \NotificationChannels\Messagebird\MessagebirdChannel::class],
            ['vonage', \Illuminate\Notifications\Channels\VonageSmsChannel::class],
            ['twilio', \NotificationChannels\Twilio\TwilioChannel::class],
        ];
    }

    /**
     * If notification facade was called properly for different channels
     *
     * @see https://laravel.com/docs/9.x/mocking#on-demand-notifications
     *
     * @dataProvider channels
     *
     * @return void
     */
    public function test_notification_invocation_ok(string $channel, string $class)
    {
        $text = 'Test text';
        $to = '+15417543010';
        config(['phone-verification.sender.channel' => $class]);

        Notification::fake();

        app(ISender::class)->invoke($to, $text);

        Notification::assertSentOnDemand(Otp::class,
            function ($notification, array $channels, AnonymousNotifiable $notifiable) use ($text, $to, $channel, $class) {
                $toMethod = 'to'.ucfirst($channel);

                return count($channels) == 1 && current($channels) === $class &&
                    $notification->$toMethod($notifiable) == $text &&
                    $notification->via($notifiable) == [$class] &&
                    $notifiable->routeNotificationFor($class) == $to;
            }
        );
    }
}
