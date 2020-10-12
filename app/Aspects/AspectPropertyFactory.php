<?php

declare(strict_types=1);

namespace App\Aspects;

/**
 * Interface AspectPropertyFactory
 * @package App\Aspects
 */
interface AspectPropertyFactory
{
    /**
     * @param string $className
     * @return object|mixed
     */
    public function make(string $className): object;
}
