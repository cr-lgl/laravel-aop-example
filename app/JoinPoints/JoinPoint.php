<?php

declare(strict_types=1);

namespace App\JoinPoints;

/**
 * Class JoinPoint
 * @package App\JoinPoints
 */
class JoinPoint
{
    /**
     * @var object
     */
    private object $instance;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $args;

    /**
     * @var bool
     */
    private bool $isCalled;

    /**
     * JoinPoint constructor.
     * @param object $instance
     * @param string $method
     * @param mixed ...$args
     */
    public function __construct(object $instance, string $method, ...$args)
    {
        $this->instance = $instance;
        $this->method = $method;
        $this->args = $args;
        $this->isCalled = false;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return get_class($this->instance);
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->args;
    }

    /**
     * @return object|null
     */
    public function proceed(): ?object
    {
        if ($this->isCalled) {
            return null;
        }

        $this->isCalled = true;

        return $this->instance->{$this->method}(...$this->args);
    }

    /**
     * @return bool
     */
    public function proceedCalled(): bool
    {
        return $this->isCalled;
    }
}
