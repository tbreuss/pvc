<?php

declare(strict_types=1);

namespace Tebe\Pvc\Event;

class Event
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var object
     */
    private $context;

    /**
     * @var array
     */
    private $info;

    /**
     * @var bool
     */
    private $cancelled = false;

    /**
     * Event constructor.
     * @param string $name
     * @param object|null $context
     * @param array|null $info
     */
    public function __construct(string $name, object $context = null, array $info = null)
    {
        $this->name = $name;
        $this->context = $context;
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return object|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return bool
     */
    public function hasContext()
    {
        return $this->context !== null;
    }

    /**
     * @return array|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return bool
     */
    public function hasInfo()
    {
        return $this->info !== null;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    /**
     * @return void
     */
    public function cancel(): void
    {
        $this->cancelled = true;
    }
}
