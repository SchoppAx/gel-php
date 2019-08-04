<?php

namespace mehrWEBnet\Gel\Api;

class Shipments extends Api
{
    public function create($parameters, $knrpos = 0)
    {
        $parameters = [ 'function' => 'create' ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }

    public function modify($snr, $parameters, $knrpos = 0)
    {
        $parameters = [ 'function' => 'modify', 'snr' => $snr ] + $parameters;
        return $this->post('', $parameters, $knrpos);
    }

    public function remove($snr, $knrpos = 0)
    {
        $parameters = [ 'function' => 'delete', 'snr' => $snr ];
        return $this->post('', $parameters, $knrpos);
    }

    public function export($snr, $load, $knrpos = 0)
    {
        $parameters = [ 'function' => 'export', 'snr' => $snr, 'load' => $load ];
        return $this->post('', $parameters, $knrpos);
    }

    public function setstatus($snr, $load, $knrpos = 0)
    {
        return $this->export($snr, $load, $knrpos);
    }
}
