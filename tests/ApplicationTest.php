<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tebe\HttpFactory\HttpFactory;
use Tebe\Pvc\Application;
use Tebe\Pvc\Config;
use Tebe\Pvc\Event\EventDispatcher;
use Tebe\Pvc\Event\EventHandler;
use Tebe\Pvc\View\View;
use Tebe\Pvc\View\ViewHelpers;

class ApplicationTest extends TestCase
{
    /** @var Application */
    private $app;

    public function setUp()
    {
        $this->app = Application::instance();
    }

    public function testSetConfig()
    {
        $this->assertInstanceOf(Application::class, $this->app->setConfig([]));
    }

    public function testGetConfig()
    {
        $this->assertInstanceOf(Config::class, $this->app->getConfig());
    }

    public function testSetRequest()
    {
        $request = (new HttpFactory())->createServerRequest('get', '/');
        $this->assertInstanceOf(Application::class, $this->app->setRequest($request));
    }

    public function testGetRequest()
    {
        $this->assertInstanceOf(ServerRequestInterface::class, $this->app->getRequest());
    }

    public function testSetView()
    {
        $view = new View(__DIR__ . '/resources/views', new ViewHelpers());
        $this->assertInstanceOf(Application::class, $this->app->setView($view));
    }

    public function testGetView()
    {
        $this->assertInstanceOf(View::class, $this->app->getView());
    }

    public function testAddMiddleware()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        $this->assertInstanceOf(Application::class, $this->app->addMiddleware($middleware));
    }

    public function testSetMiddlewares()
    {
        $middlewares = [$this->getMockBuilder(MiddlewareInterface::class)->getMock()];
        $this->assertInstanceOf(Application::class, $this->app->setMiddlewares($middlewares));
    }

    public function testSetEventDispatcher()
    {
        $this->assertInstanceOf(Application::class, $this->app->setEventDispatcher(new EventDispatcher()));
    }

    public function testGetEventDispatcher()
    {
        $this->assertInstanceOf(EventDispatcher::class, $this->app->getEventDispatcher());
    }

    public function testAddEventHandler()
    {
        $eventHandler = $this->getMockBuilder(EventHandler::class)->getMock();
        $this->assertInstanceOf(Application::class, $this->app->addEventHandler('name', $eventHandler));
    }

    public function testSetViewExtensions()
    {
        $this->assertInstanceOf(Application::class, $this->app->setViewExtensions([]));
    }
}
