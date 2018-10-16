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
            'error/404'
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

    public function testErrorActionWithExistingErrorViewFile()
    {
        $this->controller->setError(new SystemException('error', 404));
        $this->assertEquals("<div>Header</div>\nError 404<div>Footer</div>", $this->controller->errorAction());
    }

    public function testErrorActionWithMissingErrorViewFile()
    {
        $this->controller->setError(new SystemException('error', 405));
        $this->assertStringStartsWith('error<br>#0', $this->controller->errorAction());
    }

    public function testErrorActionWithJsonContentType()
    {
        $errorController = new ErrorController(
            new View(__DIR__ . '/../resources/views', new ViewHelpers()),
            ServerRequestFactory::fromGlobals(['HTTP_ACCEPT' => 'text/html,application/json;q=0.9']),
            'error/404'
        );
        $errorController->setError(new SystemException('error', 404));
        $response = $errorController->errorAction();
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('file', $response);
        $this->assertArrayHasKey('line', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('trace', $response);
    }
}
