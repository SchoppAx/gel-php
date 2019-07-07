<?php

namespace mehrWEBnet\Gel\Http;

use GuzzleHttp\Query;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\RequestInterface;

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

        $interface = $test ? 'gel' : 'geltest';

        parent::__construct([
            'base_uri' => 'https://www.service.equicon.de/' . $interface . '/api/import',
            'headers' => [
                'Content-Type' => 'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);
    }

    public function getAuthentication() {
      return [
        'key' => $this->apiKey,
        'depot' => $this->depotNr,
        'knr' => $this->knr
      ];
    }

    public function send(RequestInterface $request, array $options = [])
    {
        return parent::send($request, $options);
    }
}
