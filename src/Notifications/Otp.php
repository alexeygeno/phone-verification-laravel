<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;
use Illuminate\Notifications\Notification;
use NotificationChannels\Log\LogChannel;
use NotificationChannels\Log\LogMessage;

use Illuminate\Notifications\Channels\VonageSmsChannel;


class Otp extends Notification
{
    protected string $content;
    protected bool $toLog;

    public function __construct(bool $toLog){
        $this->toLog = $toLog;
    }

    public function content(string $content):self{
        $this->content = $content;
        return $this;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable)
    {
        return $this->toLog
            ? [LogChannel::class]
            : array_keys($notifiable->routes);
    }

    public function toVonage(object $notifiable):string
    {
        return $this->content;
    }
    public function toTwilio(object $notifiable):string
    {
        return $this->content;
    }
    public function toMessagebird(object $notifiable):string
    {
        return $this->content;
    }

    /**
     * Get the log message representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return LogMessage
     */
    public function toLog(object $notifiable):LogMessage
    {
        $route = current($notifiable->routes);
        return new LogMessage("Pretended sms send to {$route}:. ".$notifiable->routeNotificationFor($route)." with message: {$this->content} ");
    }
}
