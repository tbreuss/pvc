<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Config;
use Tebe\Pvc\Event\Event;

class EventTest extends TestCase
{
    private $event;
    private $eventWithContext;
    private $eventWithInfo;

    public function setUp()
    {
        $this->event = new Event('name');
        $this->eventWithContext = new Event('name', new Config([]));
        $this->eventWithInfo = new Event('name', null, ['param' => 123]);
    }

    public function testGetName()
    {
        $this->assertSame('name', $this->event->getName());
        $this->assertSame('name', $this->eventWithContext->getName());
        $this->assertSame('name', $this->eventWithInfo->getName());
    }

    public function testGetContext()
    {
        $this->assertInstanceOf(Config::class, $this->eventWithContext->getContext());
        $this->assertNull($this->eventWithInfo->getContext());
    }

    public function testHasContext()
    {
        $this->assertTrue($this->eventWithContext->hasContext());
        $this->assertFalse($this->eventWithInfo->hasContext());
    }

    public function testGetInfo()
    {
        $this->assertNull($this->eventWithContext->getInfo());
        $this->assertArrayHasKey('param', $this->eventWithInfo->getInfo());
    }

    public function testHasInfo()
    {
        $this->assertFalse($this->eventWithContext->hasInfo());
        $this->assertTrue($this->eventWithInfo->hasInfo());
    }

    public function testIsCancelled()
    {
        $this->assertFalse($this->event->isCancelled());
    }

    public function testCancel()
    {
        $this->event->cancel();
        $this->assertTrue($this->event->isCancelled());
    }
}
