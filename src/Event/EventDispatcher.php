<?php

declare(strict_types=1);

namespace Tebe\Pvc\Event;

class EventDispatcher
{
    /**
     * @var EventHandler[]
     */
    private $handlers = [];

    /**
     * EventDispatcher constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $eventName
     * @param EventHandler $handler
     */
    public function addHandler(string $eventName, EventHandler $handler): void
    {
        if (!isset($this->handlers[$eventName])) {
            $this->handlers[$eventName] = [];
        }
        $this->handlers[$eventName][] = $handler;
    }

    /**
     * @param string $event
     * @param object|null $context
     * @param array|null $info
     * @return Event
     */
    public function triggerEvent(string $event, object $context = null, array $info = null): Event
    {
        if (!$event instanceof Event) {
            $event = new Event($event, $context, $info);
        }
        $eventName = $event->getName();
        if (!isset($this->handlers[$eventName])) {
            return $event;
        }
        foreach ($this->handlers[$eventName] as $handler) {
            /* @var EventHandler $handler */
            $handler->handle($event);
            if ($event->isCancelled()) {
                break;
            }
        }
        return $event;
    }
}
