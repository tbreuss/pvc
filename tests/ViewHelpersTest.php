<?php

namespace Tebe\Pvc\Tests;

use ArgumentCountError;
use LogicException;
use PHPUnit\Framework\TestCase;
use Tebe\Pvc\View\ViewHelpers;
use TypeError;

class ViewHelpersTest extends TestCase
{
    private $helpers;

    public function setUp()
    {
        $this->helpers = new ViewHelpers();
    }

    public function testAdd()
    {
        $this->assertInstanceOf(ViewHelpers::class, $this->helpers->add('name', 'strtoupper'));
    }

    public function testAddWithNameConflict()
    {
        $this->expectException(LogicException::class);
        $this->helpers->add('name', 'strtoupper');
        $this->helpers->add('name', 'strtoupper');
    }

    public function testAddWithMissingName()
    {
        $this->expectException(TypeError::class);
        $this->helpers->add(null, 'strtoupper');
    }

    public function testAddWithMissingCallable()
    {
        $this->expectException(ArgumentCountError::class);
        $this->helpers->add('name');
    }

    public function testAddWithWrongTypeForCallable()
    {
        $this->expectException(TypeError::class);
        $this->helpers->add('name', 412);
    }

    public function testGet()
    {
        $this->helpers->add('name', 'strtoupper');
        $this->assertEquals('strtoupper', $this->helpers->get('name'));
    }

    public function testGetWithNonExistingName()
    {
        $this->expectException(LogicException::class);
        $this->helpers->get('name');
    }

    public function testRemove()
    {
        $this->helpers->add('name', 'strtoupper');
        $this->assertInstanceOf(ViewHelpers::class, $this->helpers->remove('name'));
    }

    public function testRemoveWithNonExistingKey()
    {
        $this->expectException(LogicException::class);
        $this->helpers->remove('name');
    }

    public function testExists()
    {
        $this->helpers->add('name', 'strtoupper');
        $this->assertEquals(true, $this->helpers->exists('name'));
    }

    public function testExistsWithNonExistingName()
    {
        $this->assertEquals(false, $this->helpers->exists('name'));
    }
}
