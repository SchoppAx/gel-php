<?php

namespace mehrWEBnet\Gel\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    public function send(RequestInterface $request, array $options = []): ResponseInterface;
}
