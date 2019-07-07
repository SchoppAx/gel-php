<?php

namespace mehrWEBnet\Gel\Api;

class Shipments extends Api
{
    public function create($body, $parameters = [])
    {
        $body = [ 'function' => 'create' ] + $body;
        return $this->post('', $parameters, $body);
    }

    public function modify($snr, $body)
    {
        $body = [ 'function' => 'modify', 'snr' => $snr ] + $body;
        return $this->post('', [], $body);
    }

    public function remove($snr)
    {
        $body = [ 'function' => 'delete', 'snr' => $snr ];
        return $this->post('', [], $body);
    }

    public function export($snr, $load)
    {
        $body = [ 'function' => 'export', 'snr' => $snr, 'load' => $load ];
        return $this->post('', [], $body);
    }

    public function setstatus($snr, $load)
    {
        return $this->export($snr, $load);
    }
}
