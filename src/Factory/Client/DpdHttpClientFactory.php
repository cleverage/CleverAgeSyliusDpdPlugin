<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Factory\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class DpdHttpClientFactory implements ClientFactoryInterface
{
    private const BASE_URI = 'http://mypudo.pickup-services.com';

    private ?HttpClientInterface $client = null;
    private string $securityKey;

    public function __construct(
        string $securityKey = ''
    )
    {
        $this->securityKey = $securityKey;
    }

    public function getClient(): HttpClientInterface
    {
        if (null === $this->client) {
            $this->client = HttpClient::createForBaseUri(self::BASE_URI);
        }

        return $this->client;
    }

    public function call(string $method, string $url, array $options = []): ResponseInterface
    {
        $baseOptions = [
            'key' => $this->securityKey,
            'carrier' => 'EXA'
        ];

        return $this->getClient()->request($method, $url, [
            'query' => array_merge($options, $baseOptions)
        ]);
    }
}
