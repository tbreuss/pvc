<?php

namespace Tebe\Pvc\Tests;

use ArgumentCountError;
use LogicException;
use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\View\View;
use Tebe\Pvc\View\ViewExtension;
use Tebe\Pvc\View\ViewHelpers;
use TypeError;

class ViewTest extends TestCase
{
    /**
     * @var View
     */
    private $view;

    public function setUp()
    {
        $helpers = new ViewHelpers();
        $helpers->add('upper', 'strtoupper');
        $helpers->add('lower', 'strtolower');
        $this->view = new View(__DIR__ . '/../resources/views', $helpers);
    }

    public function testConstructorWithNonExistingViewsPath()
    {
        $this->expectException(SystemException::class);
        new View(__DIR__ . '/not-existing-path', new ViewHelpers());
    }

    public function testConstructorWithMissingParams()
    {
        $this->expectException(ArgumentCountError::class);
        new View();
    }

    public function testConstructorWithWrongTypeForHelpers()
    {
        $this->expectException(TypeError::class);
        new View(__DIR__ . '/resources/views', false);
    }

    public function testRender()
    {
        $testValue = $this->view->render('index');
        $this->assertEquals('Output from view "index"', $testValue);
    }

    public function testRenderWithParams()
    {
        $testValue = $this->view->render('index/vars', [
            'int' => 123,
            'string' => 'ABC'
        ]);
        $this->assertEquals("Output from view \"index/vars\"<br>\n123<br>\nABC", $testValue);
    }

    public function testRenderWithNonExistingFile()
    {
        $this->expectException(SystemException::class);
        $this->view->render('not-existing-view');
    }

    public function testFileExist()
    {
        $this->assertFalse($this->view->fileExist('not-existing-view'));
        $this->assertTrue($this->view->fileExist('index/vars'));
    }

    public function testGetViewsPath()
    {
        $this->assertEquals(__DIR__ . '/../resources/views', $this->view->getViewsPath());
    }

    public function testMagicCall()
    {
        $this->assertEquals('FOO', $this->view->upper('foo'));
        $this->assertEquals('BAR', $this->view->upper('BAR'));
    }

    public function testMagicCallWithNonExistingHelper()
    {
        $this->expectException(LogicException::class);
        $this->view->notExistingMethod();
    }

    public function testRegisterHelper()
    {
        $this->assertInstanceOf(View::class, $this->view->registerHelper('trim', 'trim'));
    }

    public function testRemoveHelper()
    {
        $this->assertInstanceOf(View::class, $this->view->removeHelper('upper'));
    }

    public function testGetHelper()
    {
        $this->assertEquals('strtoupper', $this->view->getHelper('upper'));
    }

    public function testDoesHelperExist()
    {
        $this->assertTrue($this->view->doesHelperExist('upper'));
        $this->assertFalse($this->view->doesHelperExist('not-existing-helper'));
    }

    public function testRegisterExtension()
    {
        $param = $this->view;
        $extension = new class implements ViewExtension
        {
            public function register($param)
            {
            }
        };
        $testVal = $this->view->registerExtension($extension);
        $this->assertInstanceOf(View::class, $testVal);
    }

    public function testRegisterExtensionWithMissingExtension()
    {
        $this->expectException(ArgumentCountError::class);
        $this->view->registerExtension();
    }

    public function testRegisterExtensionWithWrongType()
    {
        $this->expectException(TypeError::class);
        $this->view->registerExtension(true);
    }

    public function testMagicSetGet()
    {
        $this->view->boolVal = true;
        $this->assertEquals(true, $this->view->boolVal);

        $this->view->arrayVal = ['a', 'b', 'c'];
        $this->assertEquals(['a', 'b', 'c'], $this->view->arrayVal);

        $this->view->intVal = 123;
        $this->assertEquals(123, $this->view->intVal);

        $this->view->stringVal = 'abc';
        $this->assertEquals('abc', $this->view->stringVal);
    }

    public function testMagicIssetUnset()
    {
        $this->view->boolVal = 123;
        $this->assertTrue(isset($this->view->boolVal));

        unset($this->view->boolVal);
        $this->assertFalse(isset($this->view->boolVal));
    }
}
