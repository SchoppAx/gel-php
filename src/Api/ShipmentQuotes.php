<?php

namespace mehrWEBnet\Gel\Api;

class ShipmentQuotes extends Api
{
    public function create($body, $parameters = [])
    {
        $body = [ 'function' => 'calculate' ] + $body;
        return $this->post('/', $parameters, $body);
    }
}
