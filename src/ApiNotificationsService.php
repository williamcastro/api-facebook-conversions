<?php

namespace WilliamCastro\ApiFacebookConversions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use WilliamCastro\ApiFacebookConversions\Exceptions\EmptyEventPayloadException;
use WilliamCastro\ApiFacebookConversions\Exceptions\EmptyPixelIdException;

class ApiNotificationsService
{
    protected string $pixelId;
    protected string $accessToken;
    protected Client $client;
    protected array $payload;

    /**
     * Set pixelId
     *
     * @param string $pixelId
     * @return $this
     */
    public function setPixelId(string $pixelId): ApiNotificationsService
    {
        $this->pixelId = $pixelId;

        return $this;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return $this
     */
    public function setAccessToken(string $accessToken): ApiNotificationsService
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Validate request
     *
     * @return void
     * @throws EmptyPixelIdException
     * @throws EmptyEventPayloadException
     */
    public function validate()
    {
        if (empty($this->pixelId)) {
            throw new EmptyPixelIdException();
        }

        if (empty($this->accessToken)) {
            throw new EmptyPixelIdException();
        }

        if (empty($this->payload)) {
            throw new EmptyEventPayloadException();
        }
    }

    /**
     * Get facebook URL
     *
     * @param string $endpoint
     * @return string
     */
    public function getFacebookUrl(string $endpoint = 'events'): string
    {
        return "https://graph.facebook.com/v16.0/{$this->pixelId}/{$endpoint}/?access_token={$this->accessToken}";
    }

    /**
     * Set event payload
     *
     * @param array $payload
     * @return $this
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @throws GuzzleException
     * @throws EmptyPixelIdException
     */
    public function execute()
    {
        $this->validate();

        $this->client = new Client([
                                       'headers' => [
                                           'Content-Type' => 'application/json',
                                       ]
                                   ]);

        return $this->client->request('POST', $this->getFacebookUrl(), [RequestOptions::JSON => $this->payload]);
    }
}