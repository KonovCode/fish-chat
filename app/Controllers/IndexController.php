<?php

namespace Vlad\FishChat\Controllers;

use Vlad\FishChat\core\Request;
use Vlad\FishChat\core\Response;

class IndexController
{
    public function index(Response $response): \Psr\Http\Message\ResponseInterface
    {
        return $response->create(200, json_encode(['message' => 'test object']), ['Content-Type' => 'application/json']);
    }

    public function store(Response $response): \Psr\Http\Message\ResponseInterface
    {
        return $response->create(200, json_encode(['message' => 'post data work']), ['Content-Type' => 'application/json']);
    }
}