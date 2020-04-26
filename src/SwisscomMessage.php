<?php

namespace NotificationChannels\Swisscom;

class SwisscomMessage
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var string|null
     */
    public $from = null;

    /**
     * @var string|null
     */
    public $receiver = null;

    /**
     * @var string|null
     */
    public $callback = null;

    /**
     * SwisscomMessage constructor
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set notification content
     * @param string $content
     * @return $this
     */
    public function content(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set SMS sender name or phone number
     * @param string $from
     * @return $this
     */
    public function from(string $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Set SMS receiver phone number
     * @param string $receiver
     * @return $this
     */
    public function receiver(string $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Set callback URL for SMS delivery status
     * @param string $url
     * @return $this
     */
    public function callback(string $url)
    {
        $this->callback = $url;
        return $this;
    }
}
