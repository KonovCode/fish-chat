<?php

namespace Vlad\FishChat\Events;

class SendMessage extends Event
{
    protected array $data;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}