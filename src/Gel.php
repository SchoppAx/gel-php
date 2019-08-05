<?php

namespace mehrWEBnet\Gel;

class Gel
{
    public function __construct($apiKey = null, $depotNr = null, $knr = null, $test = false)
    {
        $this->apiKey = $apiKey;
        $this->depotNr = $depotNr;
        $this->knr = $knr;
        $this->test = $test;
    }

    public static function make($apiKey = null, $depotNr = null, $knr = null, $test = false)
    {
        return new static($apiKey, $depotNr, $knr, $test);
    }

    public function __call($method, array $parameters = [])
    {
        return $this->getApiInstance($method);
    }

    protected function getApiInstance($method)
    {
        $class = "\\mehrWEBnet\\Gel\\Api\\".ucwords($method);

        if (class_exists($class)) {
            return new $class($this->apiKey, $this->depotNr, $this->knr);
        }
        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }
}
