<?php


namespace NotificationChannels\Swisscom;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use NotificationChannels\Swisscom\Exceptions\CouldNotSendNotification;

class SwisscomClient
{

    /**
     * @var string
     */
    protected $endpoint = '';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $defaultSender;

    /**
     * SwisscomClient constructor
     * @param string $apiKey
     * @param string|null $defaultSender
     */
    public function __construct(string $apiKey, string $defaultSender = null)
    {
        $this->apiKey = $apiKey;
        $this->defaultSender = $defaultSender;
    }

    public function send(string $receiver, string $message, string $sender = null, string $callback = null)
    {
        try {
            $client = new Client();
            $response = $client->post($this->endpoint, [
                'headers' => $this->getRequestHeaders(),
                'json' => [
                    'from' => $sender ?? $this->defaultSender,
                    'to' => $receiver,
                    'text' => $message,
                    'callbackUrl' => $callback ?? ''
                ]
            ]);
        } catch (BadResponseException $e) {
            // The API responded with 4XX or 5XX error
            $response = json_decode((string)$e->getResponse()->getBody());
            throw CouldNotSendNotification::serviceRespondedWithAnError($response->status, $response->message);
        } catch (ConnectException $e) {
            // A connection error (DNS, timeout, ...) occured
            throw CouldNotSendNotification::networkError();
        } catch (Exception $e) {
            // An unknown error occured
            throw CouldNotSendNotification::unknownError();
        }

        return true;
    }

    /**
     * Returns an array of headers
     * needed for sending the request
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [
            'Accept' => 'application/json',
            'client_id' => $this->apiKey,
            'SCS-Version' => '2',
        ];
    }

}
