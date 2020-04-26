<?php

namespace NotificationChannels\Swisscom\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function methodDoesNotExist()
    {
        return new static('');
    }

    public static function invalidMessage()
    {
        return new static('');
    }

    public static function missingReceiver()
    {
        return new static('');
    }

    public static function serviceRespondedWithAnError(string $code, string $message)
    {
        return new static('Swisscom SMS API responded with Code ' . $code . ': ' . $message);
    }

    public static function networkError()
    {
        return new static('');
    }

    public static function unknownError()
    {
        return new static('');
    }
}
