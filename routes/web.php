<?php

use Vlad\FishChat\Controllers\IndexController;

function webRoutes(AltoRouter $router): void
{
    // Пример маршрута для домашней страницы
    $router->map('GET', '/test', [IndexController::class, 'index']);
}
