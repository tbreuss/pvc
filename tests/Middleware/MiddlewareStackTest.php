<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tebe\HttpFactory\HttpFactory;
use Tebe\Pvc\Middleware\MiddlewareStack;

class MiddlewareStackTest extends TestCase
{
    /** @var MiddlewareStack */
    private $stack;

    public function setUp()
    {
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->stack = MiddlewareStack::create($requestHandler);
    }

    public function testCreate()
    {
        $requestHandler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $stack = MiddlewareStack::create($requestHandler);
        $this->assertInstanceOf(MiddlewareStack::class, $stack);
    }

    public function testHandle()
    {
        $request = (new HttpFactory)->createServerRequest('get', 'method');
        $response = $this->stack->handle($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPush()
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
        $stack = $this->stack->push($middleware);
        $this->assertInstanceOf(MiddlewareStack::class, $stack);
    }
}
