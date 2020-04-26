<?php

namespace NotificationChannels\Swisscom\Test;


use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\MockInterface;
use NotificationChannels\Swisscom\Exceptions\CouldNotSendNotification;
use NotificationChannels\Swisscom\SwisscomChannel;
use NotificationChannels\Swisscom\SwisscomClient;
use NotificationChannels\Swisscom\SwisscomMessage;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{

    /**
     * @var MockInterface|SwisscomClient
     */
    protected $swisscomClient;

    /** @test */
    public function it_sends_notification()
    {
        $this->swisscomClient->shouldReceive('send')
            ->with('+41791234567', 'My awesome SMS content', null, null)
            ->once()
            ->andReturnTrue();

        $channel = new SwisscomChannel($this->swisscomClient);
        $this->assertTrue($channel->send(new TestNotifiable(), new TestNotification()));
    }

    /** @test */
    public function it_converts_string_to_message_and_sends_it()
    {
        $this->swisscomClient->shouldReceive('send')
            ->with('+41791234567', 'My awesome SMS content', null, null)
            ->once()
            ->andReturnTrue();

        $channel = new SwisscomChannel($this->swisscomClient);
        $this->assertTrue($channel->send(new TestNotifiable(), new class extends Notification {
            public function toSwisscom($notifiable, $notification)
            {
                return 'My awesome SMS content';
            }
        }));
    }

    /** @test */
    public function it_throws_on_missing_toSwisscom_method()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/Swisscom channel method/');

        $channel = new SwisscomChannel($this->swisscomClient);
        $channel->send(new TestNotifiable(), new class extends Notification {
        });
    }

    /** @test */
    public function it_throws_on_invalid_message()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/message must be of type/');

        $channel = new SwisscomChannel($this->swisscomClient);
        $channel->send(new TestNotifiable(), new class extends Notification {
            public function toSwisscom($notifiable, $notification)
            {
                return true;
            }
        });
    }

    /** @test */
    public function it_throws_on_missing_receiver()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/No receiver/');

        $channel = new SwisscomChannel($this->swisscomClient);
        $channel->send(new class {
            use Notifiable;
        }, new TestNotification());
    }

    protected function setUp(): void
    {
        $this->swisscomClient = Mockery::mock(SwisscomClient::class);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForSwisscom()
    {
        return '+41791234567';
    }
}

class TestNotification extends Notification
{
    public function toSwisscom($notifiable, $notification)
    {
        return new SwisscomMessage('My awesome SMS content');
    }
}
