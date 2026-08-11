<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Api;

class ShipmentQuotes extends Api
{
    public function create(array $parameters, int $knrpos = 0): array
    {
        return $this->post('', ['function' => 'calculate'] + $parameters, $knrpos);
    }
}
