<?php

namespace mehrWEBnet\Gel\Api;

use mehrWEBnet\Gel\Http\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Api implements ApiInterface
{
    protected string $apiKey;
    protected int $depotNr;
    protected int $knr;
    protected bool $test;

    public function __construct(string $apiKey, int $depotNr, int $knr, bool $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public function get(string $uri = null, array $parameters = [], int $knrpos = 0): mixed
    {
        $body = (string) $this->execute('get', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function post(string $uri = null, array $parameters = [], int $knrpos = 0): mixed
    {
        $body = (string) $this->execute('post', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function delete(string $uri = null, array $parameters = [], int $knrpos = 0): mixed
    {
        $body = (string) $this->execute('delete', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function execute(string $httpMethod, string $uri, array $parameters = [], int $knrpos = 0): ResponseInterface
    {
        $client = $this->getClient();
        return $client->{$httpMethod}("{$uri}", [
            'query' => $client->getAuthentication($knrpos) + $parameters
        ]);
    }

    protected function getClient(): Client
    {
        return new Client($this->apiKey, $this->depotNr, $this->knr, $this->test);
    }
    
    protected function xmlToArray(string $xmlString): mixed
    {
        $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        return json_decode($json, true);
    }
}
