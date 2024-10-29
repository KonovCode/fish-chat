<?php

use Vlad\FishChat\Controllers\AuthController;
use Vlad\FishChat\Controllers\IndexController;

function webRoutes(AltoRouter $router): void
{
    $router->map('GET', '/show', [IndexController::class, 'show']);
    $router->map('POST', '/register', [AuthController::class, 'register']);
    $router->map('POST', '/login', [AuthController::class, 'login']);
    $router->map('POST', '/logout', [AuthController::class, 'logout']);
}
