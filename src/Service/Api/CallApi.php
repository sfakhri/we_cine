<?php

namespace App\Service\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class CallApi
{
    private $client;
    private $apiUrl;
    private $apiKey;
    private $auth_bearer;

    public function __construct($apiUrl, $apiBearer, $apiKey, HttpClientInterface $client,)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
        $this->auth_bearer = $apiBearer;
        $this->apiKey = $apiKey;
    }

    public function generate(array $query = [], $apiPartial = "")
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ];
        $options['auth_bearer'] = $this->auth_bearer;
        if(count($query) > 0)
            $options['query'] = $query;
        $response = $this->client->request(
            'GET',
            $this->apiUrl . $apiPartial,
            $options
        );
        if ($response->getStatusCode() === Response::HTTP_OK) {
            return $response->toArray();
        }
        return [];
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey): void
    {
        $this->apiKey = $apiKey;
    }



}