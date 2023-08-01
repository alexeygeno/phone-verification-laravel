<?php

namespace AlexGeno\PhoneVerificationLaravel\Tests\Unit;

use AlexGeno\PhoneVerificationLaravel\Notifications\Otp;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class SendersTest extends UnitTestCase
{

    public function meta():array
    {
        return [
           [
               'messagebird',
               \AlexGeno\PhoneVerificationLaravel\Senders\Messagebird::class,
           ],
           [
                'vonage',
                \AlexGeno\PhoneVerificationLaravel\Senders\Vonage::class,
           ],
            [
                'twilio',
                \AlexGeno\PhoneVerificationLaravel\Senders\Twilio::class,
            ]
        ];
    }

    /**
     * @param $channel
     * @param $sender
     * @dataProvider meta
     */
    public function test_notification_invocation_ok($channel, $sender)
    {
        $text = 'Test text';
        $to = '+380935258272';

        Notification::fake();

        app($sender)->invoke($to, $text);;

        Notification::assertSentOnDemand(Otp::class,
            function ($notification,  array $channels, AnonymousNotifiable $notifiable)  use($channel, $text, $to) {
                $toMethod = 'to'.ucfirst($channel);
                return ($notification->$toMethod($notifiable) == $text &&
                    $notification->via($notifiable) == [$channel] &&
                    $notifiable->routeNotificationFor($channel) == $to);
            }
        );

    }
}
