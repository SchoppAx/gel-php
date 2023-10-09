<?php

namespace mehrWEBnet\Gel\Http;

use GuzzleHttp\Query;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client extends \GuzzleHttp\Client implements ClientInterface
{
    protected $apiKey;
    protected $depotNr;
    protected $knr;

    public function __construct($apiKey, $depotNr, $knr, $test)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;

        $interface = $test ? 'geltest' : 'gel';

        parent::__construct([
            'base_uri' => 'https://www.service.equicon.de/' . $interface . '/api/import',
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);
    }

    public function getAuthentication($pos = 0) {
      $knr = is_array($this->knr) ? $this->knr[$pos] : $this->knr;
    	
      return [
        'key' => $this->apiKey,
        'depot' => $this->depotNr,
        'knr' => $knr
      ];
    }

    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return parent::send($request, $options);
    }
}
