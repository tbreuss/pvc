<?php

namespace Tebe\Pvc\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tebe\Pvc\Dispatcher;
use Tebe\Pvc\Router;

class RouterMiddleware implements MiddlewareInterface
{
    private $router;
    private $dispatcher;

    public function __construct(Router $router, Dispatcher $dispatcher)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serverParams = $request->getServerParams();
        $pathInfo = $serverParams['PATH_INFO'] ?? '';
        $route = $this->router->match($pathInfo);
        $pvcResponse = $this->dispatcher->dispatch($route);

        $response = $handler->handle($request);
        $stream = $response->getBody();
        $stream->write($pvcResponse->getBody());
        return $response->withBody($stream);
    }

}
