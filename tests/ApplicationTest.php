<?php

namespace Tebe\Pvc\Tests;

use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Application;

class ApplicationTest extends TestCase
{
    public function testNothing()
    {
        $app = new Application();
        $this->assertEquals(0, 0);
    }
}
