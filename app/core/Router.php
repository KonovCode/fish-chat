<?php

namespace Vlad\FishChat\core;

use AltoRouter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class Router
{
    public function __construct(private AltoRouter $router)
    {
        require_once __DIR__ . "/../../routes/web.php";

        webRoutes($this->router);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();
        $responseObject = new Response();

        $match = $this->router->match($path, $method);

        if (isset($match['target'])) {
            $target = $match['target'];
            $params = $match['params'];

            if (is_array($target)) {

                if(method_exists($target[0], $target[1])) {
                    $response = Container::getInstance()->call($target[0] . '::' . $target[1], $params);

                    if ($response instanceof ResponseInterface) {
                        return $response;
                    } else {
                        return $responseObject->create(500, "Unexpected response type");
                    }
                }
            }
        }

        return $responseObject->create(404, "not found");
    }
}