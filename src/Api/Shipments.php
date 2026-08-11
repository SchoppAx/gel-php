<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Api;

class Shipments extends Api
{
    public function create(array $parameters, int $knrpos = 0): array
    {
        return $this->post('', $this->buildPayload($parameters, 'create'), $knrpos);
    }

    public function modify(string $snr, array $parameters, int $knrpos = 0): array
    {
        return $this->post('', $this->buildPayload($parameters, 'modify', ['snr' => $snr]), $knrpos);
    }

    public function remove(string $snr, int $knrpos = 0): array
    {
        return $this->post('', $this->buildPayload([], 'delete', ['snr' => $snr]), $knrpos);
    }

    public function export(string $snr, string $load, int $knrpos = 0): array
    {
        return $this->post('', $this->buildPayload([], 'export', ['snr' => $snr, 'load' => $load]), $knrpos);
    }

    public function setstatus(string $snr, string $load, int $knrpos = 0): array
    {
        return $this->export($snr, $load, $knrpos);
    }

    private function buildPayload(array $parameters, string $function, array $extra = []): array
    {
        return ['function' => $function] + $extra + $parameters;
    }
}
