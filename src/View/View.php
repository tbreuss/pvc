<?php /** @noinspection PhpIncludeInspection */

declare(strict_types=1);

namespace Tebe\Pvc\View;

use Tebe\Pvc\Exception\SystemException;

class View
{
    // phpcs:disable

    /**
     * @var ViewHelpers
     */
    private $helpers;

    /**
     * @var string
     */
    private $__viewsPath;

    /**
     * @var array
     */
    private $__vars;

    // phpcs:enable

    /**
     * View constructor.
     * @param string $viewsPath
     * @param ViewHelpers $helpers
     * @throws SystemException
     */
    public function __construct(string $viewsPath, ViewHelpers $helpers)
    {
        $this->setViewsPath($viewsPath);
        $this->helpers = $helpers;
        $this->__vars = [];
    }

    /**
     * @param string $__viewRoute
     * @param array $__params
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
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        $callable = $this->helpers->get($name);
        return $callable(...$args);
    }

    /**
     * Register a new template function.
     * @param  string   $name;
     * @param  callback $callback;
     * @return $this
     */
    public function registerHelper(string $name, callable $callback): self
    {
        $this->helpers->add($name, $callback);
        return $this;
    }

    /**
     * Remove a template function.
     * @param  string $name;
     * @return $this
     */
    public function removeHelper(string $name): self
    {
        $this->helpers->remove($name);
        return $this;
    }

    /**
     * Get a template function.
     * @param  string $name
     * @return callable
     */
    public function getHelper(string $name): callable
    {
        return $this->helpers->get($name);
    }

    /**
     * Check if a template function exists.
     * @param  string  $name
     * @return boolean
     */
    public function doesHelperExist(string $name): bool
    {
        return $this->helpers->exists($name);
    }

    /**
     * @param ViewExtension $extension
     * @return $this
     */
    public function registerExtension(ViewExtension $extension): self
    {
        $extension->register($this);
        return $this;
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
