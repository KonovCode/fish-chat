<?php

namespace Vlad\FishChat\Handlers;

use Vlad\FishChat\core\DbConnection;
use Vlad\FishChat\Events\Event;

class CheckReceiverHandler implements EventHandlerInterface
{

    public function handle(Event $event): void
    {
        $receiverId = $event->getData()['receiver_id'] ?? null;

        if (!$receiverId) {
            throw new \Exception("Receiver ID is not provided.");
        }

        $exists = DbConnection::fetchOne(
            'SELECT id FROM `phpauth_users` WHERE `id` = :receiver_id',
            ['receiver_id' => $receiverId]
        );

        if (!$exists) {
            throw new \Exception("Receiver with ID {$receiverId} does not exist.");
        }
    }
}