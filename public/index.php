<?php

$container = require __DIR__ . '/../config/bootstrap.php';

$container->get(\Vlad\FishChat\core\ErrorHandler::class);

$requestCreator = $container->get(\Vlad\FishChat\core\Request::class);

$middleware = $container->get(\Vlad\FishChat\core\PipelineMiddleware::class);

$emitter = $container->get(\Vlad\FishChat\core\ResponseEmitter::class);

$response = $middleware->handle($requestCreator);

$emitter->emit($response);















