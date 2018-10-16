<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Controller\ErrorController;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\View\View;
use Tebe\Pvc\View\ViewHelpers;
use Throwable;
use TypeError;
use Zend\Diactoros\ServerRequestFactory;

class ErrorControllerTest extends TestCase
{
    /** @var ErrorController */
    private $controller;

    public function setUp()
    {
        $this->controller = new ErrorController(
            new View(__DIR__ . '/../resources/views', new ViewHelpers()),
            ServerRequestFactory::fromGlobals(),
            'site/about'
        );
    }

    public function testSetGetError()
    {
        $this->controller->setError(new SystemException('error'));
        $this->assertInstanceOf(Throwable::class, $this->controller->getError());
    }

    public function testSetErrorWithWrongType()
    {
        $this->expectException(TypeError::class);
        $this->controller->setError(new class {
        });
    }
}
