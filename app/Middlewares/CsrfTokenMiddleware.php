<?php

namespace Vlad\FishChat\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vlad\FishChat\core\Response;

class CsrfTokenMiddleware implements MiddlewareInterface
{
    const ALLOWED_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (!isset($_COOKIE['XSRF-TOKEN'])) {

            setcookie('XSRF-TOKEN', $_SESSION['csrf_token'], [
                'expires' => time() + 10800,
                'path' => '/',
                'secure' => true,
                'httponly' => false,
                'samesite' => 'Strict'
            ]);
        }

        if(in_array($request->getMethod(), self::ALLOWED_METHODS)) {

            if($request->getHeaderLine('X-CSRF-Token') !== $_SESSION['csrf_token']) {
                $response = new Response();
                return $response->create(403, 'CSRF token invalid');
            }

        }

        return $handler->handle($request);
    }
}