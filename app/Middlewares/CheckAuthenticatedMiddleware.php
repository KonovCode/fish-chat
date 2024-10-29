<?php

namespace Vlad\FishChat\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vlad\FishChat\Auth\Authentication;
use Vlad\FishChat\core\Response;

class CheckAuthenticatedMiddleware implements MiddlewareInterface
{
    const ALLOWED_ROUTES = ['/login', '/register'];
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $currentRoute = $request->getUri()->getPath();

        $auth = new Authentication();
        $sessionId = $request->getCookieParams()['phpauth_session_cookie'] ?? null;

        if (in_array($currentRoute, self::ALLOWED_ROUTES) && $sessionId && $auth->isLogged()) {
            return (new Response())->create(400, json_encode(['message' => 'You are already logged in.']), ['Content-Type' => 'application/json']);
        }

        if (!in_array($currentRoute, self::ALLOWED_ROUTES) && (!$sessionId || !$auth->isLogged())) {
            return (new Response())->create(401, json_encode(['message' => 'Please log in to access this resource.']), ['Content-Type' => 'application/json']);
        }

        return $handler->handle($request);
    }
}