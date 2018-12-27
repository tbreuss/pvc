<?php

namespace example\components;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResponseTimeMiddleware implements MiddlewareInterface
{
    const HEADER = 'X-Response-Time';

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $server = $request->getServerParams();
        if (!isset($server['REQUEST_TIME_FLOAT'])) {
            $server['REQUEST_TIME_FLOAT'] = microtime(true);
        }
        $response = $handler->handle($request);
        $time = (microtime(true) - $server['REQUEST_TIME_FLOAT']) * 1000;
        return $response->withHeader(self::HEADER, sprintf('%2.3fms', $time));
    }
}
