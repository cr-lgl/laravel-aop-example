<?php

declare(strict_types=1);

namespace Tests\Unit\Aspects;

use App\Aspects\PipeTransactionAspect;
use App\JoinPoints\JoinPoint;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class PipeTransactionAspectTest
 * @package Tests\Unit\Aspects
 */
class PipeTransactionAspectTest extends TestCase
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

        $aspect = new PipeTransactionAspect($connectionResolver);

        $exceptionMessage = 'Oops';
        $joinPoint = $this->createMock(JoinPoint::class);
        $joinPoint->expects($this->once())
            ->method('proceed')
            ->willThrowException(new Exception($exceptionMessage));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Oops, something went wrong');
        $aspect->handle($joinPoint, function (JoinPoint $joinPoint) {
            return $joinPoint->proceed();
        });
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

        $aspect = new PipeTransactionAspect($connectionResolver);

        $joinPoint = $this->createMock(JoinPoint::class);

        $aspect->handle($joinPoint, function (JoinPoint $joinPoint) {
            return $joinPoint->proceed();
        });
    }
}
