<?php


namespace NotificationChannels\Swisscom\Test;


use NotificationChannels\Swisscom\SwisscomMessage;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function it_accepts_content_when_constructed()
    {
        $text = 'My awesome SMS content';

        $message = new SwisscomMessage($text);
        $this->assertEquals($text, $message->content);
    }

    /** @test */
    public function it_accepts_content_using_method()
    {
        $text = 'My awesome SMS content';

        $message = (new SwisscomMessage())->text($text);
        $this->assertEquals($text, $message->content);
    }

    /** @test */
    public function its_default_sender_is_empty()
    {
        $message = new SwisscomMessage();
        $this->assertNull($message->from);
    }

    /** @test */
    public function it_accepts_sender_using_method()
    {
        $sender = 'App';

        $message = (new SwisscomMessage())->from($sender);
        $this->assertEquals($sender, $message->from);
    }

    /** @test */
    public function its_default_callback_is_empty()
    {
        $message = new SwisscomMessage();
        $this->assertNull($message->callback);
    }

    /** @test */
    public function it_accepts_callback_using_method()
    {
        $callback = 'http://my-app.com/callback';

        $message = (new SwisscomMessage())->callback($callback);
        $this->assertEquals($callback, $message->callback);
    }

    /** @test */
    public function its_default_receiver_is_empty()
    {
        $message = new SwisscomMessage();
        $this->assertNull($message->receiver);
    }

    /** @test */
    public function it_accepts_receiver_using_method()
    {
        $receiver = '+41791234567';

        $message = (new SwisscomMessage())->to($receiver);
        $this->assertEquals($receiver, $message->receiver);
    }
}
