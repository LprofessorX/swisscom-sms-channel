<?php

namespace NotificationChannels\Swisscom;

use Illuminate\Notifications\Notification;
use NotificationChannels\Swisscom\Exceptions\CouldNotSendNotification;

class SwisscomChannel
{
    /**
     * @var SwisscomClient
     */
    protected $client;

    /**
     * SwisscomChannel constructor
     * @param SwisscomClient $client
     */
    public function __construct(SwisscomClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return bool
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        // Make sure the notification has a toSwisscom method
        if (!method_exists($notification, 'toSwisscom')) {
            throw CouldNotSendNotification::methodDoesNotExist();
        }

        $message = $notification->toSwisscom($notifiable, $notification);
        if (!$message instanceof SwisscomMessage && is_string($message)) {
            $message = new SwisscomMessage($message);
        } else {
            if (!$message instanceof SwisscomMessage) {
                throw CouldNotSendNotification::invalidMessage();
            }
        }

        // Make sure a receiver is specified
        if (!method_exists($notifiable, 'routeNotificationForSwisscom') && !$message->receiver) {
            throw CouldNotSendNotification::missingReceiver();
        }

        $this->client->send(
            $message->receiver ?? $notifiable->routeNotificationFor('swisscom'),
            $message->content,
            $message->from,
            $message->callback
        );

        return true;
    }
}
