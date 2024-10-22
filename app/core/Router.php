<?php

namespace Vlad\FishChat\core;

use AltoRouter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router
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
        $responseV = new Response();

        // Совпадение маршрута
        $match = $this->router->match($path, $method);

        if (isset($match['target'])) {
            $target = $match['target'];
            $params = $match['params'];

            // Проверяем, что целевой маршрут является вызываемым
            if (is_array($target)) {

                if(method_exists($target[0], $target[1])) {
                    $controller = new $target[0]();
                    $response = call_user_func_array([$controller, $target[1]], $params);
                    if ($response instanceof ResponseInterface) {
                        return $response;
                    } else {
                        // Если метод не вернул ResponseInterface, возвращаем 500 ошибку
                        return $responseV->create(500, "Unexpected response type");
                    }
                }
            }
        }

        // Если маршрут не найден, возвращаем 404
        return $responseV->create(404, "not found");
    }
}