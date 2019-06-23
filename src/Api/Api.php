<?php

namespace mehrWEBnet\Gel\Api;

use mehrWEBnet\Gel\Http\Client;
use mehrWEBnet\Gel\ConfigInterface;

abstract class Api implements ApiInterface
{
    protected $apiKey;
    protected $depotNr;
    protected $knr;
    protected $test;

    public function __construct($apiKey, $depotNr, $knr, $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public function get($uri = null, $parameters = [])
    {
        return json_decode($this->execute('get', $uri, $parameters)->getBody(), true);
    }

    public function post($uri = null, $parameters = [], $body = [])
    {
        return json_decode($this->execute('post', $uri, $parameters, $body)->getBody(), true);
    }

    public function delete($uri = null, $parameters = [], $body = [])
    {
        return json_decode($this->execute('delete', $uri, $parameters, $body)->getBody(), true);
    }

    public function execute($httpMethod, $uri, array $parameters = [], array $body = [])
    {
        $client = $this->getClient();
        return $client->{$httpMethod}("{$uri}", [
            'query'       => $parameters,
            'json' => $client->getAuthentication() + $body
        ]);
    }

    protected function getClient()
    {
        return new Client($this->apiKey, $this->depotNr, $this->knr, $this->test);
    }
}
