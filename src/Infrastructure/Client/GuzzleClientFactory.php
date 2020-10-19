<?php

declare(strict_types=1);

namespace Smsapi\Client\Infrastructure\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Smsapi\Client\Infrastructure\Client\Decorator\GuzzleClientBaseUriDecorator;
use Smsapi\Client\Infrastructure\Client\Decorator\GuzzleClientLoggerDecorator;

/**
 * @internal
 */
class GuzzleClientFactory
{
    use LoggerAwareTrait;

    private $apiToken;
    private $uri;
    private $proxy;

    public function __construct(string $apiToken, string $uri, string $proxy)
    {
        $this->logger = new NullLogger();
        $this->apiToken = $apiToken;
        $this->uri = $uri;
        $this->proxy = $proxy;
    }

    public function createClient(): ClientInterface
    {
        $client = new GuzzleClient($this->apiToken, $this->proxy);
        $client = new GuzzleClientLoggerDecorator($client, $this->logger);
        $client = new GuzzleClientBaseUriDecorator($client, $this->uri);

        return $client;
    }
}