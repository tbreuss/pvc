<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Exception\HttpException;

class HttpExceptionTest extends TestCase
{
    public function testNotFound()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Cannot find any resource at `foo`');
        throw HttpException::notFound('foo');
    }

    public function testMethodNotAllowed()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(405);
        $this->expectExceptionMessage('Cannot access resource `foo` using method `bar`');
        throw HttpException::methodNotAllowed('foo', 'bar', []);
    }

    public function testBadRequest()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Cannot parse the request: foo');
        throw HttpException::badRequest('foo');
    }
}
