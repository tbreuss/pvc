<?php

namespace Tebe\Pvc;

class EventDispatcher
{
    private $handlers = array();

    public function __construct()
    {
    }

    public function addHandler($eventName, EventHandler $handler)
    {
        if (!isset($this->handlers[$eventName])) {
            $this->handlers[$eventName] = array();
        }
        $this->handlers[$eventName][] = $handler;
    }

    public function triggerEvent($event, $context = null, $info = null)
    {
        if (!$event instanceof Event) {
            $event = new Event($event, $context, $info);
        }
        $eventName = $event->getName();
        if (!isset($this->handlers[$eventName])) {
            return $event;
        }
        foreach ($this->handlers[$eventName] as $handler) {
            $handler->handle($event);
            if ($event->isCancelled()) {
                break;
            }
        }
        return $event;
    }
}
