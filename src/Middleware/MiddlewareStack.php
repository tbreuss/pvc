<?php

namespace Tebe\Pvc\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareStack implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares = [];

    /**
     * @param RequestHandlerInterface $requestHandler
     */
    private function __construct(RequestHandlerInterface $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param RequestHandlerInterface $requestHandler
     * @param MiddlewareInterface[] $middlewares LIFO array of middlewares
     * @return self
     */
    public static function create(RequestHandlerInterface $requestHandler, array $middlewares = []): self
    {
        $stack = new self($requestHandler);
        foreach ($middlewares as $middleware) {
            $stack = $stack->push($middleware);
        }
        return $stack;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $next = $this->peek();
        if (null === $next) {
            return $this->requestHandler->handle($request);
        }
        return $next->process($request, $this->pop());
    }

    /**
     * Creates a new stack with the given middleware pushed.
     *
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function push(MiddlewareInterface $middleware): self
    {
        $stack = clone $this;
        array_unshift($stack->middlewares, $middleware);
        return $stack;
    }

    /**
     * @return MiddlewareInterface|null
     */
    private function peek()
    {
        return reset($this->middlewares) ?: null;
    }

    /**
     * @return self
     */
    private function pop(): self
    {
        $stack = clone $this;
        array_shift($stack->middlewares);
        return $stack;
    }
}
