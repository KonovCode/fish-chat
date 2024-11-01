<?php

namespace Vlad\FishChat\core;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use Vlad\FishChat\Events\SendMessage;

class SocketConnection implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected EventManager $eventManager;
    protected array $userConnections = [];

    public function __construct(EventManager $eventManager) {
        $this->clients = new \SplObjectStorage;
        $this->eventManager = $eventManager;
    }

    public function getSessionCookie(ConnectionInterface $conn): ?string
    {
        $httpRequest = $conn->httpRequest;
        $cookies = $httpRequest->getHeader('Cookie');

        if (empty($cookies)) {
            return null;
        }

        $cookieData = [];
        parse_str(str_replace('; ', '&', implode('; ', $cookies)), $cookieData);
        return $cookieData['phpauth_session_cookie'] ?? null;
    }

    public function onOpen(ConnectionInterface $conn) {
        $uid = $this->getUidCurrentUser($conn);

        if ($uid) {
            $this->clients->attach($conn);
            $this->bindUserConnection($conn->resourceId, $uid);
            echo "New connection! User ID: ({$conn->resourceId})\n";
        } else {
            echo "Unauthorized connection attempt ({$conn->resourceId})\n";
            $conn->close();
        }
    }

    private function bindUserConnection(int $connectionId, int $uid): void
    {
        $this->userConnections[$uid] = $connectionId;
    }

    private function getUidCurrentUser(ConnectionInterface $conn): ?int
    {
        $sessionId = $this->getSessionCookie($conn);

        $user = DbConnection::fetchOne('SELECT uid FROM phpauth_sessions WHERE hash = ?', [$sessionId]);

        if($user) {
            return $user['uid'];
        }

        return null;
    }

    private function getConnectionByUid(int $uid): ?ConnectionInterface
    {
        $connectId = $this->userConnections[$uid] ?? null;

        if($connectId) {
            foreach ($this->clients as $client) {
                if($client->resourceId === $connectId) {
                   return $client;
                }
            }
        }

        return null;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data === null) {
            echo "Ошибка декодирования JSON: " . json_last_error_msg();
            return;
        }

        $data['sender_id'] = $this->getUidCurrentUser($from);
        $to = $this->getConnectionByUid($data['receiver_id']);

        $to?->send($msg);

        $messageEvent = new SendMessage($data);
        $this->eventManager->dispatch($messageEvent);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->userConnections[$this->getUidCurrentUser($conn)]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}