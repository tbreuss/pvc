<?php

namespace Tebe\Pvc\Tests;

use ArgumentCountError;
use LogicException;
use PHPUnit\Framework\TestCase;
use Tebe\Pvc\View\ViewHelpers;
use TypeError;

class ViewHelpersTest extends TestCase
{
    public function testAdd()
    {
        $helpers = new ViewHelpers();

        // return value
        $foo = function () {
            return;
        };
        $bar = function () {
            return;
        };

        $return = $helpers->add('foo', $foo);
        $this->assertInstanceOf(ViewHelpers::class, $return);

        $helpers->add('bar', $bar);
        $boolToTest = $helpers->exists('bar');
        $this->assertEquals(true, $boolToTest);

        $funcToTest = $helpers->get('foo');
        $this->assertEquals($foo, $funcToTest);

        // key exists
        $this->expectException(LogicException::class);
        $helpers->add('foo', function () {
            return;
        });
    }

    public function testAddArgumentCountError()
    {
        $helpers = new ViewHelpers();

        $this->expectException(ArgumentCountError::class);
        $helpers->add('a');
    }

    public function testAddTypeError()
    {
        $helpers = new ViewHelpers();

        $this->expectException(TypeError::class);
        $helpers->add('foo', 'bar');
    }

    public function testGet()
    {
        $helpers = new ViewHelpers();
        $func = 'strtoupper';
        $helpers->add('foo', $func);

        $funcToTest = $helpers->get('foo');
        $this->assertEquals($func, $funcToTest);

        $this->expectException(LogicException::class);
        $helpers->get('not-existing-key');
    }

    public function testRemove()
    {
        $helpers = new ViewHelpers();
        $func = 'strtoupper';
        $helpers->add('foo', $func);

        $return = $helpers->remove('foo');
        $this->assertEquals($helpers, $return);

        $this->expectException(LogicException::class);
        $helpers->remove('not-existing-key');
    }

    public function testExists()
    {
        $helpers = new ViewHelpers();
        $helpers->add('foo', 'strtoupper');

        $boolToTest = $helpers->exists('foo');
        $this->assertEquals(true, $boolToTest);

        $boolToTest = $helpers->exists('not-existing-key');
        $this->assertEquals(false, $boolToTest);
    }
}
