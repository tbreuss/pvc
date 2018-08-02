<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Tebe\Pvc\Helper\Assert;

class Router
{
    private $controllersPath;

    /**
     * Router constructor.
     * @param string $controllersPath
     */
    public function __construct(string $controllersPath)
    {
        $this->setControllersPath($controllersPath);
    }

    /**
     * @param string $pathInfo
     * @return Route
     * @throws \Exception
     */
    public function match(string $pathInfo): Route
    {
        $pathInfo = $this->sanitizePathInfo($pathInfo);

        list($controllerName) = explode('/', $pathInfo);
        $controllerPath = sprintf('%s/%sController.php', $this->controllersPath, ucfirst($controllerName));
        $controllerClass = ucfirst($controllerName) . 'Controller';

        $route = new Route(
            $pathInfo,
            $controllerPath,
            $controllerClass
        );

        return $route;
    }

    /**
     * @return string
     */
    public function getControllersPath(): string
    {
        return $this->controllersPath;
    }

    /**
     * @param string $controllersPath
     */
    private function setControllersPath(string $controllersPath)
    {
        Assert::isDirectory($controllersPath, 'Controllers path "%s" does not exist');
        $this->controllersPath = $controllersPath;
    }

    /**
     * @param string $pathInfo
     * @return string
     */
    private function sanitizePathInfo(string $pathInfo)
    {
        $pathInfo = trim($pathInfo, '/');
        if (empty($pathInfo)) {
            $pathInfo .= 'index/index';
        }
        if (strpos($pathInfo, '/') === false) {
            $pathInfo .= '/index';
        }
        return $pathInfo;
    }
}
