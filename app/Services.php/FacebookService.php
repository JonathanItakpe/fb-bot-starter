<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
* FaceBook Service
*/
class FacebookService
{
	/**
     * FB Messenger API Url
     *
     * @var string
     */
    protected $apiUrl = 'https://graph.facebook.com/v2.7/';

    /**
     * @var null|string
     */
    protected $token = null;

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * FbBotApp constructor.
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;

        $this->client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => $this->apiUrl,
		    'headers' => [
		    	'Content-Type' => 'application/json'
		    ]
		]);
    }

    public function send($payload)
    {
        return $this->makeApiCall('me/messages', $payload);
    }

    private function makeApiCall($url, $payload)
    {
		$payload['access_token'] = $this->token;

		$response = $this->client->post($url, ['query' => $payload, 'json' => json_encode($payload)]);

		return json_decode($response->getBody(), true);
    }
} 
?>