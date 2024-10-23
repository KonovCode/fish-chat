<?php

require __DIR__ . "/../vendor/autoload.php";

require __DIR__ . "/../config/error_handler.php";

$container = \Vlad\FishChat\core\Container::getInstance();

$requestCreator = $container->get(\Vlad\FishChat\core\Request::class);

$middleware = $container->get(\Vlad\FishChat\core\PipelineMiddleware::class);

$emitter = $container->get(\Vlad\FishChat\core\ResponseEmitter::class);

$response = $middleware->handle($requestCreator);

$emitter->emit($response);















