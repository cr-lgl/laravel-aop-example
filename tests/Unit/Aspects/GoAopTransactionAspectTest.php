<?php

declare(strict_types=1);

namespace Tests\Unit\Aspects;

use App\Aspects\AspectPropertyFactory;
use App\Aspects\GoAopTransactionAspect;
use Exception;
use Go\Aop\Intercept\MethodInvocation;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class GoAopTransactionAspectTest
 * @package Tests\Unit\Aspects
 */
class GoAopTransactionAspectTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function fail_around_transactional_will_transaction_rollback_and_rethrow(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->once())->method('rollback');

        $connectionResolver = $this->createMock(ConnectionResolverInterface::class);
        $connectionResolver->method('connection')
            ->willReturn($connection);

        $factory = $this->createMock(AspectPropertyFactory::class);
        $factory->expects($this->once())
            ->method('make')
            ->willReturn($connectionResolver);

        $aspect = new GoAopTransactionAspect($factory);

        $invocation = $this->createMock(MethodInvocation::class);
        $exceptionMessage = 'Oops';
        $invocation->expects($this->once())
            ->method('proceed')
            ->willThrowException(new Exception($exceptionMessage));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops, something went wrong');
        $aspect->aroundTransactional($invocation);
    }

    /**
     * @test
     *
     * @return void
     */
    public function success_around_transactional_will_transaction_commit(): void
    {
        $connection = $this->createMock(ConnectionInterface::class);
        $connection->expects($this->once())->method('commit');

        $connectionResolver = $this->createMock(ConnectionResolverInterface::class);
        $connectionResolver->method('connection')
            ->willReturn($connection);

        $factory = $this->createMock(AspectPropertyFactory::class);
        $factory->expects($this->once())
            ->method('make')
            ->willReturn($connectionResolver);

        $aspect = new GoAopTransactionAspect($factory);
        $invocation = $this->createMock(MethodInvocation::class);

        $aspect->aroundTransactional($invocation);
    }
}
