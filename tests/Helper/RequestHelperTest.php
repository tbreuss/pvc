<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Helper\RequestHelper;
use Zend\Diactoros\ServerRequestFactory;

class RequestHelperTest extends TestCase
{
    public function testGetPathInfoWithEmptySegment()
    {
        $server = ServerRequestFactory::fromGlobals(['PATH_INFO' => '']);
        $this->assertEquals('index/index', RequestHelper::getPathInfo($server));
    }

    public function testGetPathInfoWithOneSegment()
    {
        $server = ServerRequestFactory::fromGlobals(['PATH_INFO' => 'news']);
        $this->assertEquals('news/index', RequestHelper::getPathInfo($server));
    }

    public function testGetPathInfoWithTwoSegments()
    {
        $server = ServerRequestFactory::fromGlobals(['PATH_INFO' => 'site/contact']);
        $this->assertEquals('site/contact', RequestHelper::getPathInfo($server));
    }
}
