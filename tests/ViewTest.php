<?php

namespace Tebe\Pvc\Tests;

use ArgumentCountError;
use LogicException;
use PHPUnit\Framework\TestCase;
use Tebe\Pvc\Exception\SystemException;
use Tebe\Pvc\View\View;
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
        $helpers->add('upper', function ($str) {
            return strtoupper($str);
        });
        $helpers->add('lower', function ($str) {
            return strtolower($str);
        });
        $this->view = new View(__DIR__ . '/resources/views', $helpers);
    }

    public function testConstructorException1()
    {
        $this->expectException(SystemException::class);
        new View(__DIR__ . '/not-existing-path', new ViewHelpers());
    }

    public function testConstructorException2()
    {
        $this->expectException(ArgumentCountError::class);
        new View();
    }

    public function testConstructorException3()
    {
        $this->expectException(TypeError::class);
        new View(__DIR__ . '/resources/views', false);
    }

    public function testRender()
    {
        // render without params
        $testValue = $this->view->render('index');
        $this->assertEquals('Output from view "index"', $testValue);

        // render with params
        $testValue = $this->view->render('index/vars', [
            'int' => 123,
            'string' => 'ABC'
        ]);
        $this->assertEquals("Output from view \"index/vars\"<br>\n123<br>\nABC", $testValue);

        // file not exist
        $this->expectException(SystemException::class);
        $this->view->render('not-existing-view');
    }

    public function testFileExist()
    {
        // existing view
        $testValue = $this->view->fileExist('index/vars');
        $this->assertEquals(true, $testValue);

        // not existing view
        $testValue = $this->view->fileExist('not-existing-view');
        $this->assertEquals(false, $testValue);
    }

    public function testGetViewsPath()
    {
        $testValue = $this->view->getViewsPath();
        $this->assertEquals(__DIR__ . '/resources/views', $testValue);
    }

    public function testCall()
    {
        $upper = $this->view->upper('foo');
        $this->assertEquals('FOO', $upper);

        $lower = $this->view->upper('BAR');
        $this->assertEquals('BAR', $lower);

        $this->expectException(LogicException::class);
        $this->view->notExisting();
    }

    public function testData()
    {
        // __set / __get
        $boolVal = true;
        $this->view->boolVal = $boolVal;
        $this->assertEquals($boolVal, $this->view->boolVal);

        $arrayVal = ['a', 'b', 'c'];
        $this->view->arrayVal = $arrayVal;
        $this->assertEquals($arrayVal, $this->view->arrayVal);

        $intVal = 123;
        $this->view->intVal = $intVal;
        $this->assertEquals($intVal, $this->view->intVal);

        $stringVal = 'abc';
        $this->view->stringVal = $stringVal;
        $this->assertEquals($stringVal, $this->view->stringVal);

        // __isset / __unset
        $boolVal = isset($this->view->boolVal);
        $this->assertEquals(true, $boolVal);
        unset($this->view->boolVal);
        $boolVal = isset($this->view->boolVal);
        $this->assertEquals(false, $boolVal);

        $nullVal = $this->view->boolVal;
        $this->assertEquals(null, $nullVal);
    }
}
