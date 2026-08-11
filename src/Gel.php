<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel;

use BadMethodCallException;
use mehrWEBnet\Gel\Api\ShipmentQuotes;
use mehrWEBnet\Gel\Api\Shipments;

class Gel
{
    private string $apiKey;
    private ?int $depotNr;
    private int|array|null $knr;
    private bool $test;

    public function __construct(string $apiKey, ?int $depotNr = null, int|array|null $knr = null, bool $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public static function make(string $apiKey, ?int $depotNr = null, int|array|null $knr = null, bool $test = false): self
    {
        return new self($apiKey, $depotNr, $knr, $test);
    }

    public function shipments(): Shipments
    {
        return $this->getApiInstance('shipments');
    }

    public function shipmentQuotes(): ShipmentQuotes
    {
        return $this->getApiInstance('shipmentQuotes');
    }

    protected function getApiInstance(string $method): Shipments|ShipmentQuotes
    {
        $className = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $method)));
        $class = sprintf('\\mehrWEBnet\\Gel\\Api\\%s', $className);

        if (!class_exists($class)) {
            throw new BadMethodCallException("Undefined method [{$method}] called.");
        }

        return new $class($this->apiKey, $this->depotNr, $this->knr, $this->test);
    }
}
