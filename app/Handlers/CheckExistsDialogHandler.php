<?php

namespace Vlad\FishChat\Handlers;

use Vlad\FishChat\core\DbConnection;
use Vlad\FishChat\Events\Event;

class CheckExistsDialogHandler implements EventHandlerInterface
{

    public function handle(Event $event): void
    {
        $data = $event->getData();

        $exists = DbConnection::fetchOne(
            'SELECT id FROM `dialog` 
                 WHERE (`sender_id` = :sender_id AND `receiver_id` = :receiver_id) 
                    OR (`sender_id` = :receiver_id AND `receiver_id` = :sender_id) 
                 LIMIT 1',
            [
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id']
            ]
        );

        if(!$exists) {
            DbConnection::execute(
                'INSERT INTO dialog (sender_id, receiver_id, created) VALUES (:sender_id, :receiver_id, :created)',
                [
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                    'created' => date('Y-m-d H:i:s')
                ]
            );
        }
    }
}