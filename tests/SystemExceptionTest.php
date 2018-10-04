<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Exception\SystemException;

class SystemExceptionTest extends TestCase
{
    public function testClassNotExist()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Class "UrlHelper" does not exist');
        throw SystemException::classNotExist('UrlHelper');
    }

    public function testMethodNotExist()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Method "indexAction" does not exist');
        throw SystemException::methodNotExist('indexAction');
    }

    public function testIncludeFileNotExist()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Include file "include_file.php" does not exist');
        throw SystemException::includeFileNotExist('include_file.php');
    }

    public function testDirectoryNotExist()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Directory "/var/www" does not exist');
        throw SystemException::directoryNotExist('/var/www');
    }

    public function testFileNotExist()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('File "/var/www/index.html" does not exist');
        throw SystemException::fileNotExist('/var/www/index.html');
    }

    public function testServerError()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Unexpected Server Error');
        throw SystemException::serverError('Unexpected Server Error');
    }
}
