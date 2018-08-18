<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Application;

class ApplicationTest extends TestCase
{
    public function testNothing()
    {
        Application::instance(['viewsPath' => __DIR__]);
        $this->assertEquals(0, 0);
    }
}
