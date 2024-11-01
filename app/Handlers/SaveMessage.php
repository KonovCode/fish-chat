<?php

namespace Vlad\FishChat\Handlers;

use Vlad\FishChat\core\DbConnection;
use Vlad\FishChat\Events\Event;

class SaveMessage implements EventHandlerInterface
{
    public function handle(Event $event): void
    {
        $data = $event->getData();

        $dialog = DbConnection::fetchOne(
            'SELECT id FROM `dialog` 
                 WHERE (`sender_id` = :sender_id AND `receiver_id` = :receiver_id) 
                    OR (`sender_id` = :receiver_id AND `receiver_id` = :sender_id) 
                 LIMIT 1',
            [
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id']
            ]
        );

        if ($dialog) {
            $message = DbConnection::execute(
                'INSERT INTO `message` (dialog_id, user_id, text, created) VALUES (:dialog_id, :user_id, :text, :created)',
                [
                    'dialog_id' => $dialog['id'],
                    'user_id' => $data['sender_id'],
                    'text' => $data['content'],
                    'created' => date('Y-m-d H:i:s')
                ]
            );

            if (!$message) {
                throw new \Exception('Error saving message');
            }
        } else {
            throw new \Exception('Dialog not exists');
        }
    }
}