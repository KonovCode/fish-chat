<?php

require __DIR__ . "/../vendor/autoload.php";

require __DIR__ . "/../config/error_handler.php";

$container = require_once __DIR__ . "/../config/di-container.php";

$requestCreator = $container->get(\Nyholm\Psr7Server\ServerRequestCreator::class)->fromGlobals();

$middleware = $container->get(\Vlad\FishChat\core\PipelineMiddleware::class);

$emitter = $container->get(\Vlad\FishChat\core\ResponseEmitter::class);

$response = $middleware->handle($requestCreator);

$emitter->emit($response);















