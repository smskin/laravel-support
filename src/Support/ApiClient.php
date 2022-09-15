<?php

namespace SMSkin\LaravelSupport\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

abstract class ApiClient
{
    public function __construct(protected string $host)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $uri, array $queryParams = [], array $headers = []): ResponseInterface
    {
        $params = count($queryParams) ? '?'.http_build_query($queryParams) : '';

        return $this->getClient()->request(
            'GET',
            $this->host.$uri.$params,
            [
                'headers' => array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                ),
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $uri, array $body = [], array $headers = []): ResponseInterface
    {
        return $this->getClient()->request(
            'POST',
            $this->host.$uri,
            [
                RequestOptions::JSON => $body,
                'headers' => array_merge(
                    $this->getDefaultHeaders(),
                    $headers
                ),
            ]
        );
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function getClient(): Client
    {
        return app(Client::class);
    }
}