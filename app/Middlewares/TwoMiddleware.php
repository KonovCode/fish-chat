<?php

namespace Vlad\FishChat\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vlad\FishChat\core\Response;

class TwoMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if(false) {
            $response = new Response();
            var_dump($response->create(403, 'middelware two status ok'));
            exit();
        }

        return $handler->handle($request);
    }
}