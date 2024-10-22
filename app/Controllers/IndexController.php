<?php

namespace Vlad\FishChat\Controllers;

use Vlad\FishChat\core\Response;

class IndexController
{
    public function index(): \Psr\Http\Message\ResponseInterface
    {
        $response = new Response();

        return $response->create(200, json_encode(['message' => 'controller working']), ['Content-Type', 'application/json']);
    }
}