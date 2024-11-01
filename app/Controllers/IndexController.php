<?php

namespace Vlad\FishChat\Controllers;

use Vlad\FishChat\core\Response;

class IndexController
{
    public function index(Response $response): \Psr\Http\Message\ResponseInterface
    {
        return $response->create(200, json_encode(['message' => 'login page']), ['Content-Type' => 'application/json']);
    }

    public function show(Response $response): \Psr\Http\Message\ResponseInterface
    {
        return $response->create(200, json_encode([
            'data' => [
                'testOne' => ['1' => 'one', '2' => 'two', '3' => 'three'],
                'testTwo' => ['11' => 'result two', '12' => 'result three', '13' => 'result four'],
            ],
        ]), ['Content-Type' => 'application/json']);
    }
}