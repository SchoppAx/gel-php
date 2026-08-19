<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Http;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @method ResponseInterface get(string $uri, array $options = [])
 */
class Client implements ClientInterface
{
    private string $apiKey;
    private int $depotNr;
    private int|array $knr;
    private GuzzleClient $httpClient;

    public function __construct(string $apiKey, int $depotNr, int|array $knr, bool $test)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;

        $interface = $test ? 'geltest' : 'gel';

        $this->httpClient = new GuzzleClient([
            'base_uri' => sprintf('https://www.service.equicon.de/%s/api/import', $interface),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
    }

    public function getAuthentication(int $pos = 0): array
    {
        $knr = is_array($this->knr) ? ($this->knr[$pos] ?? null) : $this->knr;

        return [
            'key' => $this->apiKey,
            'depot' => $this->depotNr,
            'knr' => $knr,
        ];
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->httpClient->send($request, $options);
    }

    public function __call(string $method, array $arguments): mixed
    {
        return $this->httpClient->{$method}(...$arguments);
    }
}
