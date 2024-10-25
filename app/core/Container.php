<?php

namespace Vlad\FishChat\core;

use DI\ContainerBuilder;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

class Container
{
    private static ?\DI\Container $container = null;

    public static function getInstance(): \DI\Container
    {
        if (self::$container === null) {
            $builder = new ContainerBuilder();

            $builder->addDefinitions([

                \Nyholm\Psr7\Factory\Psr17Factory::class => \DI\create(\Nyholm\Psr7\Factory\Psr17Factory::class),

                ServerRequestCreator::class => \DI\factory(function (\Nyholm\Psr7\Factory\Psr17Factory $psr17Factory) {
                    return new ServerRequestCreator(
                        $psr17Factory,
                        $psr17Factory,
                        $psr17Factory,
                        $psr17Factory
                    );
                }),

                'base_server_request' => \DI\factory(fn(ServerRequestCreator $creator) => $creator->fromGlobals()),

                ServerRequestInterface::class => \DI\factory(fn($c) => new Request($c->get('base_server_request'))),

                \AltoRouter::class => \DI\create(\AltoRouter::class),

                \Monolog\Logger::class => \DI\create(\Monolog\Logger::class)->constructor($_ENV['LOGGER_NAME']),

                \Vlad\FishChat\core\ResponseEmitter::class => \DI\create(\Vlad\FishChat\core\ResponseEmitter::class),

                \Vlad\FishChat\core\Router::class => \DI\autowire(\Vlad\FishChat\core\Router::class),

                \Vlad\FishChat\core\ErrorHandler::class => \DI\autowire(\Vlad\FishChat\core\ErrorHandler::class),

                \Vlad\FishChat\core\PipelineMiddleware::class => \DI\autowire(\Vlad\FishChat\core\PipelineMiddleware::class)
                    ->method('addMiddleware', \DI\get(\Vlad\FishChat\Middlewares\CorsMiddleware::class))
                    ->method('addMiddleware', \DI\get(\Vlad\FishChat\Middlewares\CsrfTokenMiddleware::class))

            ]);

            self::$container = $builder->build();
        }

        return self::$container;
    }
}