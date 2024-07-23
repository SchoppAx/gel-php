<?php

namespace mehrWEBnet\Gel\Api;

class ShipmentQuotes extends Api
{
    public function create(array $parameters, int $knrpos = 0): mixed
    {
        $parameters = [ 'function' => 'calculate' ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }
}
