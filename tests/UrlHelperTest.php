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
        $tests = ['/', 'http://example.com', 'https://example.com'];

        foreach ($tests as $test) {
            $url = UrlHelper::to($test);
            $this->assertEquals($url, $test);
        }

        // testing wrong type
        $this->expectException(InvalidArgumentException::class);
        UrlHelper::to(0.4521);
    }

    public function testToRoute()
    {
        $tests = [
            [['/'], $this->scriptName],
            [['index'], $this->scriptName . '/index'],
            [['/index'], $this->scriptName . '/index'],
            [['///index'], $this->scriptName . '/index'],
            [['index/'], $this->scriptName . '/index'],
            [['index///'], $this->scriptName . '/index'],
            [['index/index'], $this->scriptName . '/index/index'],
            [['index/features'], $this->scriptName . '/index/features'],
            [['news/list'], $this->scriptName . '/news/list'],
            [['news/detail', 'id' => 23], $this->scriptName . '/news/detail?id=23'],
            [
                ['index/contact', 'a' => 1, 'b' => 2, '#' => 'anchor'],
                $this->scriptName . '/index/contact?a=1&b=2#anchor'
            ],
        ];

        // testing different routes
        foreach ($tests as $test) {
            $url = UrlHelper::toRoute($test[0]);
            $this->assertEquals($url, $test[1]);
        }

        // testing type error
        $this->expectException(TypeError::class);
        UrlHelper::toRoute('index/index');

        // testing invalid argument
        $this->expectException(InvalidArgumentException::class);
        UrlHelper::toRoute([]);
    }
}
