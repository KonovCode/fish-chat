<?php

namespace Vlad\FishChat\Handlers;

use Vlad\FishChat\Events\Event;

interface EventHandlerInterface
{
    public function handle(Event $event): void;
}