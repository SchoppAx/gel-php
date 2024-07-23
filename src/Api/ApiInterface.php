<?php

namespace mehrWEBnet\Gel\Api;

use Psr\Http\Message\ResponseInterface;

interface ApiInterface
{
    public function get(string $url = null, array $parameters = [], int $knrpos = 0): mixed;

    public function post(string $url = null, array $parameters = [], int $knrpos = 0): mixed;

    public function delete(string $url = null, array $parameters = [], int $knrpos = 0): mixed;

    public function execute(string $httpMethod, string $url, array $parameters = [], int $knrpos = 0): ResponseInterface;
}
