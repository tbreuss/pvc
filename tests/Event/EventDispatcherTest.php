<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Event\Event;
use Tebe\Pvc\Event\EventDispatcher;
use Tebe\Pvc\Event\EventHandler;

class EventDispatcherTest extends TestCase
{
    /** @var EventDispatcher */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addHandler('handler', new class implements EventHandler {
            public function handle(Event $event): void
            {
                $event->getContext()->param = 'triggered';
            }
        });
    }

    public function testAddHandler()
    {
        $eventHandler = $this->getMockBuilder(EventHandler::class)->getMock();
        $this->assertInstanceOf(EventDispatcher::class, $this->dispatcher->addHandler('name', $eventHandler));
    }

    public function testTriggerEvent()
    {
        $object = new class {
            public $param;
        };
        $event = $this->dispatcher->triggerEvent('handler', $object);
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals('triggered', $object->param);
    }
}
