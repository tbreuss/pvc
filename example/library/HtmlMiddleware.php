<?php

namespace example\library;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HtmlMiddleware implements MiddlewareInterface
{
    const MODE_PREPEND = 0;
    const MODE_APPEND = 1;

    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $mode;

    /**
     * HtmlMiddleware constructor.
     * @param string $string
     * @param int $mode
     */
    public function __construct(string $string, int $mode = 0)
    {
        $this->string = $string;
        $this->mode = $mode;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // see: https://github.com/jshannon63/laravel-psr15-middleware/blob/master/src/exampleMiddleware.php
        $response->getBody()->rewind();
        $body = $response->getBody();
        $contents = $body->getContents();

        if (static::MODE_PREPEND == $this->mode) {
            $contents = str_replace(
                '<body>',
                "<body>".$this->string,
                $contents
            );
        }
        if (static::MODE_APPEND == $this->mode) {
            $contents = str_replace(
                '</body>',
                $this->string.'</body>',
                $contents
            );
        }

        $body->rewind();
        $body->write($contents);

        return $response->withBody($body);
    }
}
