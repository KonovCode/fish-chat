<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Vlad\FishChat\core\PipelineMiddleware;
use Vlad\FishChat\core\ResponseEmitter;
use Vlad\FishChat\core\Router;

$container = new DI\Container();

$builder = new DI\ContainerBuilder();

$builder->addDefinitions([
    Psr17Factory::class => \DI\create(Psr17Factory::class),

    ServerRequestCreator::class => DI\factory(function (Psr17Factory $psr17Factory) {
        return new ServerRequestCreator(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory
        );
    }),

    AltoRouter::class => DI\create(AltoRouter::class),

    ResponseEmitter::class => DI\create(ResponseEmitter::class),

    Router::class => \DI\autowire(\Vlad\FishChat\core\Router::class),

    PipelineMiddleware::class => DI\autowire(PipelineMiddleware::class)
            ->method('addMiddleware', DI\get(\Vlad\FishChat\Middlewares\OneMiddleware::class))
            ->method('addMiddleware', DI\get(\Vlad\FishChat\Middlewares\TwoMiddleware::class)),
]);

return $builder->build();

