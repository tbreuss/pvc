<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Controller\BaseController;
use Tebe\Pvc\View\View;
use Tebe\Pvc\View\ViewHelpers;

class BaseControllerTest extends TestCase
{
    /** @var BaseController */
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockForAbstractClass(BaseController::class, [
            'view' => new View(__DIR__ . '/../resources/views', new ViewHelpers()),
            'controller/action'
        ]);
    }

    public function testGetView()
    {
        $this->assertInstanceOf(View::class, $this->mock->getView());
    }

    public function testGetControllerName()
    {
        $this->assertEquals('controller', $this->mock->getControllerName());
    }

    public function testGetActionName()
    {
        $this->assertEquals('action', $this->mock->getActionName());
    }

    public function testGetActionMethod()
    {
        $this->assertEquals('actionAction', $this->mock->getActionMethod());
    }

    public function testGetRoute()
    {
        $this->assertEquals('controller/action', $this->mock->getRoute());
    }

    public function testHttMethods()
    {
        $this->assertEquals([], $this->mock->httpMethods());
    }

    public function testGetAllowedHttpMethods()
    {
        $this->assertEquals(['GET'], $this->mock->getAllowedHttpMethods());
    }
}
