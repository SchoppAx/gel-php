<?php

namespace mehrWEBnet\Gel\Api;

class Shipments extends Api
{
    public function create(array $parameters, int $knrpos = 0): mixed
    {
        $parameters = [ 'function' => 'create' ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }

    public function modify(string $snr, array $parameters, int $knrpos = 0): mixed
    {
        $parameters = [ 'function' => 'modify', 'snr' => $snr ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }

    public function remove(string $snr, int $knrpos = 0): mixed
    {
        $parameters = [ 'function' => 'delete', 'snr' => $snr ];
        return $this->post('', $parameters, $knrpos);
    }

    public function export(string $snr, string $load, int $knrpos = 0): mixed
    {
        $parameters = [ 'function' => 'export', 'snr' => $snr, 'load' => $load ];
        return $this->post('', $parameters, $knrpos);
    }

    public function setstatus(string $snr, string $load, int $knrpos = 0): mixed
    {
        return $this->export($snr, $load, $knrpos);
    }
}
