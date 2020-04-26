<?php

namespace NotificationChannels\Swisscom\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Descriptive error message.");
    }
}
