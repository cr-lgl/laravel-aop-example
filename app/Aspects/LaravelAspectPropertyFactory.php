<?php

declare(strict_types=1);

namespace App\Aspects;

/**
 * Class LaravelAspectPropertyFactory
 * @package App\Aspects
 */
class LaravelAspectPropertyFactory implements AspectPropertyFactory
{
    /**
     * @inheritDoc
     */
    public function make(string $className): object
    {
        return resolve($className);
    }
}
