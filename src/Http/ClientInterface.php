<?php

namespace mehrWEBnet\Gel\Http;

use Psr\Http\Message\RequestInterface;

interface ClientInterface
{
    public function send(RequestInterface $request, array $options = []);
}
