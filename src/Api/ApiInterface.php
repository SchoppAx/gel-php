<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Api;

use Psr\Http\Message\ResponseInterface;

interface ApiInterface
{
    public function get(string $url = '', array $parameters = [], int $knrpos = 0): array;

    public function post(string $url = '', array $parameters = [], int $knrpos = 0): array;

    public function delete(string $url = '', array $parameters = [], int $knrpos = 0): array;

    public function execute(string $httpMethod, string $url, array $parameters = [], int $knrpos = 0): ResponseInterface;
}
