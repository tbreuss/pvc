<?php

namespace example\library;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HeaderMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $value;

    /**
     * HeaderMiddleware constructor.
     * @param string $header
     * @param string $value
     */
    public function __construct(string $header, string $value)
    {
        $this->header = $header;
        $this->value = $value;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $response = $handler->handle($request);
        return $response->withHeader($this->header, $this->value);
    }
}
