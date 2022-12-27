<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Factory\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ClientFactoryInterface
{
    public function getClient(): HttpClientInterface;

    public function call(string $method, string $url, array $options = []): ResponseInterface;
}
