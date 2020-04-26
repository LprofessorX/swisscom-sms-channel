<?php


namespace NotificationChannels\Swisscom\Test;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use NotificationChannels\Swisscom\Exceptions\CouldNotSendNotification;
use NotificationChannels\Swisscom\SwisscomClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    protected $guzzleClient;

    /** @test */
    public function it_sends_sms_successfully()
    {
        $apiKey = '1234';
        $sender = '+41799999999';
        $receiver = '+41791234567';
        $text = 'My awesome content';

        $this->guzzleClient->shouldReceive('post')
            ->with('https://api.swisscom.com/messaging/sms', [
                'headers' => [
                    'Accept' => 'application/json',
                    'client_id' => $apiKey,
                    'SCS-Version' => '2',
                ],
                'json' => [
                    'from' => $sender,
                    'to' => $receiver,
                    'text' => $text
                ]
            ])->once()->andReturnTrue();

        $client = new SwisscomClient($apiKey, $sender, $this->guzzleClient);
        $this->assertTrue($client->send($receiver, $text));
    }

    /** @test */
    public function it_sends_sms_with_runtime_sender_successfully()
    {
        $apiKey = '1234';
        $configuredSender = '+41788888888';
        $passedSender = '+41799999999';
        $receiver = '+41791234567';
        $text = 'My awesome content';

        $this->guzzleClient->shouldReceive('post')
            ->with('https://api.swisscom.com/messaging/sms', [
                'headers' => [
                    'Accept' => 'application/json',
                    'client_id' => $apiKey,
                    'SCS-Version' => '2',
                ],
                'json' => [
                    'from' => $passedSender,
                    'to' => $receiver,
                    'text' => $text
                ]
            ])->once()->andReturnTrue();

        $client = new SwisscomClient($apiKey, $configuredSender, $this->guzzleClient);
        $this->assertTrue($client->send($receiver, $text, $passedSender));
    }

    /** @test */
    public function it_sends_sms_with_callback_successfully()
    {
        $apiKey = '1234';
        $sender = '+41799999999';
        $receiver = '+41791234567';
        $text = 'My awesome content';
        $callback = 'http://localhost/callback';

        $this->guzzleClient->shouldReceive('post')
            ->with('https://api.swisscom.com/messaging/sms', [
                'headers' => [
                    'Accept' => 'application/json',
                    'client_id' => $apiKey,
                    'SCS-Version' => '2',
                ],
                'json' => [
                    'from' => $sender,
                    'to' => $receiver,
                    'text' => $text,
                    'callbackUrl' => $callback
                ]
            ])->once()->andReturnTrue();

        $client = new SwisscomClient($apiKey, $sender, $this->guzzleClient);
        $this->assertTrue($client->send($receiver, $text, $sender, $callback));
    }

    /** @test */
    public function it_handles_http_client_and_server_errors()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/Swisscom SMS API responded with Code 401/');

        $guzzleClient = new Client([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(401, [], json_encode([
                    "status" => "401",
                    "code" => "INVALID_AUTHENTICATION_CREDENTIALS",
                    "message" => "Authentication credentials missing or incorrect.",
                    "detail" => ""
                ]))
            ]))
        ]);

        $client = new SwisscomClient('1234', '079', $guzzleClient);
        $client->send('078', 'Hello');
    }

    /** @test */
    public function it_handles_network_errors()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/Swisscom API server/');

        $this->guzzleClient->shouldReceive('post')->withAnyArgs()->once()
            ->andThrow(ConnectException::class, '', Mockery::mock(Request::class));

        $client = new SwisscomClient('1234', '079', $this->guzzleClient);
        $this->assertTrue($client->send('079', 'Hello'));
    }

    protected function setUp(): void
    {
        $this->guzzleClient = Mockery::mock(Client::class);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
