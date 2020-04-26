<?php

namespace NotificationChannels\Swisscom\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function methodDoesNotExist()
    {
        return new static('The Swisscom channel method is missing on the notification instance');
    }

    public static function invalidMessage()
    {
        return new static('The message must be of type SwisscomMessage or string');
    }

    public static function missingReceiver()
    {
        return new static('No receiver was specified');
    }

    public static function serviceRespondedWithAnError(string $code, string $message)
    {
        return new static('Swisscom SMS API responded with Code ' . $code . ': ' . $message);
    }

    public static function networkError()
    {
        return new static('The Swisscom API server couldn\'t be reached');
    }

    public static function unknownError()
    {
        return new static('An unknown error occured');
    }
}
