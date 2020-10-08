<?php

declare(strict_types=1);

namespace App\Services;

use App\Aspects\PipeLoggingAspect;
use App\Aspects\PipeTransactionAspect;
use App\JoinPoints\JoinPoint;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;

/**
 * Class ProxyCreateUserService
 * @package App\Services
 */
class ProxyCreateUserService implements CreateUserService
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var CommonCreateUserService
     */
    private CommonCreateUserService $createUserService;

    /**
     * ProxyCreateUserService constructor.
     * @param Container $container
     * @param CommonCreateUserService $createUserService
     */
    public function __construct(Container $container, CommonCreateUserService $createUserService)
    {
        $this->container = $container;
        $this->createUserService = $createUserService;
    }

    /**
     * @inheritDoc
     */
    public function run(string $email, string $password): void
    {
        (new Pipeline($this->container))
            ->send(new JoinPoint($this->createUserService, 'run', $email, $password))
            ->through([
                PipeLoggingAspect::class,
                PipeTransactionAspect::class,
            ])
            ->then(function (JoinPoint $joinPoint) {
                $joinPoint->proceed();
            });
    }
}
