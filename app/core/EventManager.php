<?php

namespace Vlad\FishChat\core;

use Vlad\FishChat\Events\Event;
use Vlad\FishChat\Handlers\EventHandlerInterface;

class EventManager
{
    private array $handlers = [];

    public function subscribe(string $eventClass, EventHandlerInterface $handler): void
    {
        $this->handlers[$eventClass][] = $handler;
    }

    public function dispatch(Event $event): void
    {
        $eventClass = get_class($event);

        if (!empty($this->handlers[$eventClass])) {
            foreach ($this->handlers[$eventClass] as $handler) {
                $handler->handle($event);
            }
        }
    }
}