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
    const ALLOWED_ROUTES = ['/login', '/register'];
    const SESSION_LIFETIME = 3600;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_expires']) || $_SESSION['csrf_expires'] < time()) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_expires'] = time() + self::SESSION_LIFETIME;
        }

        setcookie('XSRF-TOKEN', $_SESSION['csrf_token'], [
            'expires' => time() + self::SESSION_LIFETIME,
            'path' => '/',
            'secure' => false,
            'httponly' => false,
            'samesite' => 'Strict',
        ]);

        if(in_array($request->getMethod(), self::ALLOWED_METHODS)) {
            if(!in_array($request->getUri()->getPath(), self::ALLOWED_ROUTES) && $request->getHeaderLine('X-CSRF-Token') !== $_SESSION['csrf_token']) {
                return (new Response())->create(403, 'CSRF token invalid');
            }
        }

        return $handler->handle($request);
    }
}