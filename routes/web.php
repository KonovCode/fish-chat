<?php

use Vlad\FishChat\Controllers\IndexController;

function webRoutes(AltoRouter $router): void
{
    $router->map('GET', '/test', [IndexController::class, 'index']);
    $router->map('POST', '/store', [IndexController::class, 'store']);
}
