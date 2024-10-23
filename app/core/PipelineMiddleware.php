<?php

namespace Vlad\FishChat\core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PipelineMiddleware implements RequestHandlerInterface
{
    public function __construct(private readonly Router $router)
    {
    }

    private array $middlewares = [];

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if(empty($this->middlewares)) {
           return $this->router->handle($request);
        }

        $middleware = array_shift($this->middlewares);

        return $middleware->process($request, $this);
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }
}