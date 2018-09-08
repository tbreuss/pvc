<?php

namespace Tebe\Pvc\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewarePipe implements MiddlewareInterface
{
    /**
     * @var ServerMiddlewareInterface[]
     */
    private $middlewares = [];

    private function __construct()
    {
    }

    /**
     * @param MiddlewareInterface[] $middlewares FIFO array of middlewares
     * @return self
     */
    public static function create(array $middlewares = []): self
    {
        $pipe = new self();
        foreach ($middlewares as $middleware) {
            $pipe = $pipe->add($middleware);
        }
        return $pipe;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $requestHandler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        if (empty($this->middlewares)) {
            return $requestHandler->handle($request);
        }
        $stack = MiddlewareStack::create($requestHandler, array_reverse($this->middlewares));
        return $stack->handle($request);
    }

    /**
     * Creates a new pipe with the given middleware connected.
     *
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function add(MiddlewareInterface $middleware): self
    {
        $pipe = clone $this;
        array_push($pipe->middlewares, $middleware);
        return $pipe;
    }
}
