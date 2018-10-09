<?php

declare(strict_types=1);

namespace Tebe\Pvc\Event;

interface EventHandler
{
    /**
     * @param Event $event
     */
    public function handle(Event $event): void;
}
