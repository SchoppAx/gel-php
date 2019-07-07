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
        return $this->xmlToArray($this->execute('get', $uri, $parameters, $body)->getBody());
    }

    public function post($uri = null, $parameters = [], $body = [])
    {
        return $this->xmlToArray($this->execute('post', $uri, $parameters, $body)->getBody());
    }

    public function delete($uri = null, $parameters = [], $body = [])
    {
        return $this->xmlToArray($this->execute('delete', $uri, $parameters, $body)->getBody());
    }

    public function execute($httpMethod, $uri, array $parameters = [], array $body = [])
    {
        $client = $this->getClient();
        return $client->{$httpMethod}("{$uri}", [
            'query'       => $parameters,
            'form_params' => $client->getAuthentication() + $body
        ]);
    }

    protected function getClient()
    {
        return new Client($this->apiKey, $this->depotNr, $this->knr, $this->test);
    }
    
    protected function xmlToArray($xmlString)
    {
        $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		return json_decode($json, true);
    }
}
