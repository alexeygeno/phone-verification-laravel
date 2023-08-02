<?php

namespace AlexGeno\PhoneVerificationLaravel\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Log\LogChannel;
use NotificationChannels\Log\LogMessage;

class Otp extends Notification
{
    protected string $content;

    protected bool $toLog;

    public function __construct(bool $toLog)
    {
        $this->toLog = $toLog;
    }

    public function content(string $content)
    {
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

    /**
     * Returns $this->content for the methods: toMessagebird, toTwilio, toVonage, ...
     * If you need custom behaviour just add the "to{Driver}" method to this class
     *
     * @return string
     */
    public function __call(string $name, array $args)
    {
        if (str_starts_with($name, 'to') and count($args) == 1 and is_object(current($args))) {
            return $this->content;
        }
    }

    /**
     * Get the log message representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return LogMessage
     */
    public function toLog(object $notifiable)
    {
        $routes = implode(',', $notifiable->routes);

        return new LogMessage("Pretended sms send to {$routes}:. ".$notifiable->routeNotificationFor($route)." with message: {$this->content} ");
    }
}
