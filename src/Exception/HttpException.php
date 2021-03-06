<?php

declare(strict_types=1);

namespace Tebe\Pvc\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class HttpException extends Exception
{
    /**
     * @var array
     */
    private $allowed = [];

    /**
     * @param string $path
     * @param string|null $format
     * @return static
     */
    public static function notFound(string $path, string $format = null): HttpException
    {
        $format = $format ?? 'Cannot find any resource at `%s`';
        $message = sprintf($format, $path);
        return new static($message, 404);
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $allowed
     * @param string|null $format
     * @return static
     */
    public static function methodNotAllowed(
        string $path,
        string $method,
        array $allowed,
        string $format = null
    ): HttpException {
        $format = $format ?? 'Cannot access resource `%s` using method `%s`';
        $message = sprintf($format, $path, $method);
        $error = new static($message, 405);
        $error->allowed = $allowed;
        return $error;
    }

    /**
     * @param string $path
     * @param string|null $format
     * @return static
     */
    public static function badRequest(string $path, string $format = null): HttpException
    {
        $format = $format ?? 'Cannot parse the request: %s';
        return new static(sprintf(
            $format,
            $path
        ), 400);
    }

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
