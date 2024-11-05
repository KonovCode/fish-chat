<?php
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

$container = require __DIR__ . '/config/bootstrap.php';

$eventManager = $container->get(\Vlad\FishChat\core\EventManager::class);

$eventManager->subscribe(\Vlad\FishChat\Events\SendMessage::class, new \Vlad\FishChat\Handlers\CheckReceiverHandler());
$eventManager->subscribe(\Vlad\FishChat\Events\SendMessage::class, new \Vlad\FishChat\Handlers\CheckExistsDialogHandler());
$eventManager->subscribe(\Vlad\FishChat\Events\SendMessage::class, new \Vlad\FishChat\Handlers\SaveMessage());

$socket = $container->get(\Vlad\FishChat\core\SocketConnection::class);

$server = IoServer::factory(
    new HttpServer(new WsServer($socket)),
    8088
);

$server->run();