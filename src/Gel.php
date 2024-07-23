<?php

namespace mehrWEBnet\Gel;

use mehrWEBnet\Gel\Api\ShipmentQuotes;
use mehrWEBnet\Gel\Api\Shipments;

class Gel
{
    private string $apiKey;
    private int $depotNr;
    private int $knr;
    private bool $test;

    public function __construct(string $apiKey = null, int $depotNr = null, int $knr = null, bool $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public static function make(string $apiKey = null, int $depotNr = null, int $knr = null, bool $test = false): Gel
    {
        return new static($apiKey, $depotNr, $knr, $test);
    }

    /**
     * @param string $method
     * @param array  $parameters
     *
     * @return Shipments|ShipmentQuotes
     */
    public function __call(string $method, array $parameters = [])
    {
        return $this->getApiInstance($method);
    }

    /**
     * Get class
     *
     * @param string $method Api method to call
     * @return Shipments|ShipmentQuotes
     * @throws BadMethodCallException
     */
    protected function getApiInstance(string $method)
    {
        $class = "\\mehrWEBnet\\Gel\\Api\\".ucwords($method);

        if (class_exists($class)) {
            return new $class($this->apiKey, $this->depotNr, $this->knr, $this->test);
        }
        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }
}
