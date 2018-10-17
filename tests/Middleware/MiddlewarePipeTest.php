<?php

namespace Tebe\Pvc\Tests;

use ArgumentCountError;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tebe\HttpFactory\HttpFactory;
use Tebe\Pvc\Middleware\MiddlewarePipe;
use TypeError;

class MiddlewarePipeTest extends TestCase
{
    /** @var MiddlewarePipe */
    private $pipe;

    public function setUp()
    {
        $this->pipe = MiddlewarePipe::create([]);
    }

    public function testCreate()
    {
        $this->assertInstanceOf(MiddlewarePipe::class, MiddlewarePipe::create([]));
    }

    public function testCreateWithWrongType()
    {
        $this->expectException(TypeError::class);
        MiddlewarePipe::create('wrongType');
    }

    public function testAdd()
    {
        $middleware = new class implements MiddlewareInterface
        {
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return HttpFactory::createResponse(200);
            }
        };
        $pipe = $this->pipe->add($middleware);
        $this->assertInstanceOf(MiddlewarePipe::class, $pipe);
    }

    public function testAddWithMissingType()
    {
        $this->expectException(TypeError::class);
        $this->pipe->add();
    }

    public function testAddWithWrongType()
    {
        $this->expectException(TypeError::class);
        $this->pipe->add('type');
    }

    public function testProcess()
    {
        $request = (new HttpFactory)->createServerRequest('GET', 'uri');
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $responseInterface = $this->pipe->process($request, $requestHandler);
        $this->assertInstanceOf(ResponseInterface::class, $responseInterface);
    }

    public function testProcessWithMissingRequest()
    {
        $this->expectException(TypeError::class);
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->pipe->process(null, $requestHandler);
    }

    public function testProcessWithMissingHandler()
    {
        $this->expectException(TypeError::class);
        $request = (new HttpFactory)->createServerRequest('GET', 'uri');
        $this->pipe->process($request);
    }

    public function testProcessWithMissingParams()
    {
        $this->expectException(ArgumentCountError::class);
        $this->pipe->process();
    }
}
