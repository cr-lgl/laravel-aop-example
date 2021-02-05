<?php

namespace Tests;

use Closure;
use Go\Core\AspectContainer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function tearDown(): void
    {
        Closure::fromCallable(function () {
            /** @noinspection PhpUndefinedFieldInspection */
            $this->tags = [];
        })->call(resolve(AspectContainer::class));

        parent::tearDown();
    }
}
