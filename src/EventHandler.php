<?php

declare(strict_types=1);

namespace Tebe\Pvc;

interface EventHandler
{
    public function handle(Event $event): void;
}
