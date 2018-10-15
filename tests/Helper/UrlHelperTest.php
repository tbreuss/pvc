<?php

namespace Tebe\Pvc\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Helper\UrlHelper;
use TypeError;

class UrlHelperTest extends TestCase
{
    private $scriptName = '/index.php';

    public function setUp()
    {
        $_SERVER['SCRIPT_NAME'] = $this->scriptName;
    }

    public function testTo()
    {
        $this->assertEquals('/', UrlHelper::to('/'));
        $this->assertEquals('http://example.com', UrlHelper::to('http://example.com'));
        $this->assertEquals('https://example.com', UrlHelper::to('https://example.com'));
    }

    public function testToWithWrongType()
    {
        $this->expectException(InvalidArgumentException::class);
        UrlHelper::to(0.4521);
    }

    public function testToRoute()
    {
        $this->assertEquals($this->scriptName, UrlHelper::toRoute(['/']));
        $this->assertEquals($this->scriptName . '/index', UrlHelper::toRoute(['index']));
        $this->assertEquals($this->scriptName . '/index', UrlHelper::toRoute(['/index']));
        $this->assertEquals($this->scriptName . '/index', UrlHelper::toRoute(['//index']));
        $this->assertEquals($this->scriptName . '/index', UrlHelper::toRoute(['index/']));
        $this->assertEquals($this->scriptName . '/index', UrlHelper::toRoute(['index//']));
        $this->assertEquals($this->scriptName . '/index/index', UrlHelper::toRoute(['index/index']));
        $this->assertEquals($this->scriptName . '/index/features', UrlHelper::toRoute(['index/features']));
        $this->assertEquals($this->scriptName . '/news/list', UrlHelper::toRoute(['news/list']));
        $this->assertEquals($this->scriptName . '/news/detail?id=23', UrlHelper::toRoute(['news/detail', 'id' => 23]));
        $this->assertEquals(
            $this->scriptName . '/index/contact?a=1&b=2#anchor',
            UrlHelper::toRoute(['index/contact', 'a' => 1, 'b' => 2, '#' => 'anchor'])
        );
    }

    public function testToRouteWithWrongType()
    {
        $this->expectException(TypeError::class);
        UrlHelper::toRoute('index/index');
    }

    public function testToRouteWithEmptyArray()
    {
        $this->expectException(InvalidArgumentException::class);
        UrlHelper::toRoute([]);
    }
}
