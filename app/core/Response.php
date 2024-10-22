<?php

namespace Vlad\FishChat\core;

use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response as NyholmResponse;

class Response
{
    public function create(int $status = 200, string $body = '', array $headers = []): ResponseInterface
    {
        return new NyholmResponse($status, $headers, $body);
    }
}