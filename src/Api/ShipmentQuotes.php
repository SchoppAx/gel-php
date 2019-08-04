<?php

namespace mehrWEBnet\Gel\Api;

class ShipmentQuotes extends Api
{
    public function create($parameters, $knrpos = 0)
    {
        $parameters = [ 'function' => 'calculate' ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }
}
