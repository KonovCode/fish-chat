<?php

require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$container = \Vlad\FishChat\core\Container::getInstance();

$container->get(\Vlad\FishChat\core\ErrorHandler::class);

$requestCreator = $container->get(\Vlad\FishChat\core\Request::class);

$middleware = $container->get(\Vlad\FishChat\core\PipelineMiddleware::class);

$emitter = $container->get(\Vlad\FishChat\core\ResponseEmitter::class);

$response = $middleware->handle($requestCreator);

$emitter->emit($response);















