<?php

namespace Vlad\FishChat\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vlad\FishChat\core\Response;

class CorsMiddleware implements MiddlewareInterface
{
    private const ALLOWED_ORIGIN = '*';
    private const ALLOWED_METHODS = ['GET', 'POST'];
    private const ALLOWED_HEADERS = ['Content-Type'];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getHeaderLine('Origin') !== self::ALLOWED_ORIGIN && self::ALLOWED_ORIGIN !== '*') {
            return (new Response())->create(403, 'Forbidden');
        }

        if ($request->getMethod() === 'OPTIONS') {
            return (new Response())->create(
                200,
                'CORS',
                [
                    'Access-Control-Allow-Origin' => self::ALLOWED_ORIGIN,
                    'Access-Control-Allow-Methods' => implode(', ', self::ALLOWED_METHODS),
                    'Access-Control-Allow-Headers' => implode(', ', self::ALLOWED_HEADERS),
                ]
            );
        }

        if (!in_array($request->getMethod(), self::ALLOWED_METHODS)) {
            return (new Response())->create(405, 'Method Not Allowed');
        }

        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', self::ALLOWED_ORIGIN)
            ->withHeader('Access-Control-Allow-Methods', implode(', ', self::ALLOWED_METHODS))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', self::ALLOWED_HEADERS));
    }
}