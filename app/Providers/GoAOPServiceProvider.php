<?php

declare(strict_types=1);

namespace App\Providers;

use App\Aspects\GoAopLoggingAspect;
use App\Aspects\GoAopTransactionAspect;
use Illuminate\Support\ServiceProvider;

/**
 * Class GoAOPServiceProvider
 * @package App\Providers
 */
class GoAOPServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->app->singleton(GoAopLoggingAspect::class, GoAopLoggingAspect::class);
        $this->app->tag([GoAopLoggingAspect::class], ['goaop.aspect']);

        $this->app->singleton(GoAopTransactionAspect::class, GoAopTransactionAspect::class);
        $this->app->tag([GoAopTransactionAspect::class], ['goaop.aspect']);
    }
}
