<?php

declare(strict_types=1);

namespace Tebe\Pvc\View;

use LogicException;

/**
 * A collection of helper functions.
 */
class ViewHelpers
{
    /**
     * Array of template functions.
     * @var callable[]
     */
    private $helpers = [];

    /**
     * Add a new template function.
     * @param  string $name
     * @param  callback $callback
     * @return ViewHelpers
     */
    public function add(string $name, callable $callback): self
    {
        if ($this->exists($name)) {
            throw new LogicException(
                'The helper function name "' . $name . '" is already registered.'
            );
        }

        $this->helpers[$name] = $callback;

        return $this;
    }

    /**
     * Remove a template function.
     * @param  string $name
     * @return ViewHelpers
     */
    public function remove(string $name): self
    {
        if (!$this->exists($name)) {
            throw new LogicException(
                'The template function "' . $name . '" was not found.'
            );
        }

        unset($this->helpers[$name]);

        return $this;
    }

    /**
     * Get a template function.
     * @param  string $name
     * @return callable
     */
    public function get(string $name): callable
    {
        if (!$this->exists($name)) {
            throw new LogicException('The template function "' . $name . '" was not found.');
        }

        return $this->helpers[$name];
    }

    /**
     * Check if a template function exists.
     * @param  string $name
     * @return boolean
     */
    public function exists(string $name): bool
    {
        return isset($this->helpers[$name]);
    }
}
