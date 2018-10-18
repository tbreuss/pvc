<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tebe\HttpFactory\HttpFactory;
use Tebe\Pvc\Middleware\MiddlewarePipe;

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

    public function testProcess()
    {
        $request = (new HttpFactory)->createServerRequest('GET', 'uri');
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $responseInterface = $this->pipe->process($request, $requestHandler);
        $this->assertInstanceOf(ResponseInterface::class, $responseInterface);
    }
}
