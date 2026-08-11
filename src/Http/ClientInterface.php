<?php

declare(strict_types=1);

namespace mehrWEBnet\Gel\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function getAuthentication(int $pos = 0): array;

    public function send(RequestInterface $request, array $options = []): ResponseInterface;
}
