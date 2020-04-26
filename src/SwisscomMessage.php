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
     * SwisscomMessage constructor.
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public function content(string $content)
    {
        $this->content = $content;
        return $this;
    }

    public function from(string $from)
    {
        $this->from = $from;
        return $this;
    }

    public function receiver(string $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function callback(string $url)
    {
        $this->callback = $url;
        return $this;
    }
}
