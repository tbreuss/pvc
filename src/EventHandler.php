<?php

namespace Tebe\Pvc;

interface EventHandler {
    public function handle(Event $event);
}
