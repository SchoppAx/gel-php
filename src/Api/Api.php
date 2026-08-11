<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Api;

use mehrWEBnet\Gel\Http\Client;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

abstract class Api implements ApiInterface
{
    protected string $apiKey;
    protected int $depotNr;
    protected int|array $knr;
    protected bool $test;

    public function __construct(string $apiKey, int $depotNr, int|array $knr, bool $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public function get(string $uri = '', array $parameters = [], int $knrpos = 0): array
    {
        $body = (string) $this->execute('get', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function post(string $uri = '', array $parameters = [], int $knrpos = 0): array
    {
        $body = (string) $this->execute('post', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function delete(string $uri = '', array $parameters = [], int $knrpos = 0): array
    {
        $body = (string) $this->execute('delete', $uri, $parameters, $knrpos)->getBody();
        return $this->xmlToArray($body);
    }

    public function execute(string $httpMethod, string $uri, array $parameters = [], int $knrpos = 0): ResponseInterface
    {
        $client = $this->getClient();
        $query = $client->getAuthentication($knrpos) + $parameters;

        return $client->{$httpMethod}($uri, ['query' => $query]);
    }

    protected function getClient(): Client
    {
        return new Client($this->apiKey, $this->depotNr, $this->knr, $this->test);
    }

    protected function xmlToArray(string $xmlString): array
    {
        if ($xmlString === '') {
            return [];
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString, SimpleXMLElement::class, LIBXML_NOCDATA);
        libxml_clear_errors();

        if ($xml === false) {
            return [];
        }

        return $this->normalizeXml($xml);
    }

    private function normalizeXml(SimpleXMLElement $node): array|string
    {
        $attributes = [];
        foreach ($node->attributes() as $name => $value) {
            $attributes[$name] = (string) $value;
        }

        $children = $node->children();
        if ($children->count() === 0) {
            $value = trim((string) $node);
            if ($attributes === []) {
                return $value;
            }

            return $attributes + ['value' => $value];
        }

        $result = [];
        foreach ($children as $child) {
            $name = $child->getName();
            $childValue = $this->normalizeXml($child);

            if (!array_key_exists($name, $result)) {
                $result[$name] = $childValue;
                continue;
            }

            if (!is_array($result[$name])) {
                $result[$name] = [$result[$name]];
            }

            $result[$name][] = $childValue;
        }

        if ($attributes !== []) {
            $result['@attributes'] = $attributes;
        }

        return $result;
    }
}
