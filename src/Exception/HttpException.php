<?php

declare(strict_types=1);

namespace Tebe\Pvc\Exception;

use Psr\Http\Message\ResponseInterface;
use Exception;

class HttpException extends Exception
{
    /**
     * @param string $path
     * @return static
     */
    public static function notFound($path): HttpException
    {
        return new static(sprintf(
            'Cannot find any resource at `%s`',
            $path
        ), 404);
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $allowed
     *
     * @return static
     */
    public static function methodNotAllowed($path, $method, array $allowed): HttpException
    {
        $error = new static(sprintf(
            'Cannot access resource `%s` using method `%s`',
            $path,
            $method
        ), 405);

        $error->allowed = $allowed;

        return $error;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public static function badRequest($message): HttpException
    {
        return new static(sprintf(
            'Cannot parse the request: %s',
            $message
        ), 400);
    }

    /**
     * @var array
     */
    private $allowed = [];

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function withResponse(ResponseInterface $response): ResponseInterface
    {
        if (!empty($this->allowed)) {
            $response = $response->withHeader('Allow', implode(',', $this->allowed));
        }

        return $response;
    }
}
