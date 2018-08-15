<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Tebe\Pvc\Exception\SystemException;

class Route
{
    /**
     * @var string
     */
    private $pathInfo;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var string
     */
    private $controllerPath;

    /**
     * Route constructor.
     * @param string $pathInfo
     * @param string $controllerPath
     * @param string $controllerClass
     */
    public function __construct(string $pathInfo, string $controllerPath, string $controllerClass)
    {
        $this->setPathInfo($pathInfo);
        $this->setControllerPath($controllerPath);
        $this->setControllerClass($controllerClass);
    }

    /**
     * @return string
     */
    public function getControllerPath(): string
    {
        return $this->controllerPath;
    }

    /**
     * @param string $controllerPath
     * @throws SystemException
     */
    private function setControllerPath(string $controllerPath)
    {
        if (!is_file($controllerPath)) {
            throw SystemException::classNotExist($controllerPath);
        }
        require_once($controllerPath);
        $this->controllerPath = $controllerPath;
    }

    /**
     * @param string $controllerClass
     * @throws SystemException
     */
    private function setControllerClass(string $controllerClass)
    {
        if (!class_exists($controllerClass, false)) {
            throw SystemException::classNotExist($controllerClass);
        }
        $this->controllerClass = $controllerClass;
    }

    /**
     * @return string
     */
    public function getControllerClassName(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        $parts = $this->getPathInfoParts();
        return $parts[1];
    }

    /**
     * @return string
     * @throws SystemException
     */
    public function getActionMethod(): string
    {
        $actionMethod = $this->getActionName() . 'Action';
        if (!method_exists($this->controllerClass, $actionMethod)) {
            throw SystemException::methodNotExist($actionMethod);
        }
        return $actionMethod;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        $parts = $this->getPathInfoParts();
        return $parts[0];
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }

    /**
     * @param string $pathInfo
     */
    private function setPathInfo(string $pathInfo)
    {
        $this->pathInfo = $pathInfo;
    }

    /**
     * @return array
     */
    private function getPathInfoParts() : array
    {
        return explode('/', $this->pathInfo);
    }
}
