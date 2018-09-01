<?php

declare(strict_types=1);

namespace Tebe\Pvc;

use Tebe\Pvc\Exception\SystemException;

class View
{
    /**
     * @var array
     */
    private $__helpers;

    /**
     * @var string
     */
    private $__viewsPath;

    /**
     * @var array
     */
    private $__vars;

    /**
     * View constructor.
     * @param string $viewsPath
     * @throws SystemException
     */
    public function __construct(string $viewsPath)
    {
        $this->setViewsPath($viewsPath);
        $this->__helpers = [];
        $this->__vars = [];
    }

    /**
     * @param string $viewRoute
     * @param array $params
     * @return string
     * @throws SystemException
     */
    public function render(string $__viewRoute, array $__params = []): string
    {
        $__viewPath = $this->resolvePath($__viewRoute);
        if (!is_file($__viewPath)) {
            throw SystemException::fileNotExist($__viewPath);
        }
        extract($__params);
        ob_start();
        require $__viewPath;
        $html = ob_get_clean();
        return $html;
    }

    /**
     * @param string $viewRoute
     * @return string
     */
    private function resolvePath(string $viewRoute): string
    {
        $viewPath = sprintf(
            '%s/%s.php',
            $this->__viewsPath,
            $viewRoute
        );
        return $viewPath;
    }

    /**
     * @param string $viewRoute
     * @return bool
     */
    public function fileExist(string $viewRoute): bool
    {
        $viewPath = $this->resolvePath($viewRoute);
        return is_file($viewPath);
    }

    /**
     * @return string
     */
    public function getViewsPath(): string
    {
        return $this->__viewsPath;
    }

    /**
     * @param string $viewsPath
     * @throws SystemException
     */
    private function setViewsPath(string $viewsPath)
    {
        if (!is_dir($viewsPath)) {
            throw SystemException::directoryNotExist($viewsPath);
        }
        $this->__viewsPath = $viewsPath;
    }

    /**
     * @param string $methodName
     * @param array $args
     * @return mixed
     * @throws SystemException
     */
    public function __call(string $methodName, array $args)
    {
        $helper = $this->loadViewHelper($methodName);
        $value = $helper->execute($args);
        return $value;
    }

    /**
     * @param string $helper
     * @return ViewHelper
     * @throws SystemException
     */
    private function loadViewHelper(string $helper): ViewHelper
    {
        $helperName = ucfirst($helper);
        if (!isset($this->__helpers[$helper])) {
            $className = 'Tebe\\Pvc\\ViewHelper\\' . $helperName . 'ViewHelper';
            $fileName  = __DIR__ . "/ViewHelper/{$helperName}ViewHelper.php";
            if (!is_file($fileName)) {
                throw SystemException::fileNotExist($fileName, 'View helper "%s" does not exist');
            }
            $this->__helpers[$helper] = new $className();
        }
        return $this->__helpers[$helper];
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if (array_key_exists($name, $this->__vars)) {
            return $this->__vars[$name];
        }
        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->__vars[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->__vars[$name]);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        unset($this->__vars[$name]);
    }

}
