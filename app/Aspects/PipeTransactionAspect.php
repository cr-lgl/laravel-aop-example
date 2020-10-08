<?php

declare(strict_types=1);

namespace App\Aspects;

use App\JoinPoints\JoinPoint;
use Closure;
use Illuminate\Database\ConnectionResolverInterface;
use RuntimeException;
use Throwable;

/**
 * Class PipeTransactionAspect
 * @package App\Aspects
 */
class PipeTransactionAspect
{
    /**
     * @var ConnectionResolverInterface
     */
    private ConnectionResolverInterface $connectionResolver;

    /**
     * PipeTransactionAspect constructor.
     * @param ConnectionResolverInterface $connectionResolver
     */
    public function __construct(ConnectionResolverInterface $connectionResolver)
    {
        $this->connectionResolver = $connectionResolver;
    }

    /**
     * @param JoinPoint $joinPoint
     * @param Closure $closure
     * @return mixed
     */
    public function handle(JoinPoint $joinPoint, Closure $closure)
    {
        $this->connectionResolver->connection()->beginTransaction();

        try {
            $response = $closure($joinPoint);

            $this->connectionResolver->connection()->commit();
        } catch (Throwable $exception) {
            $this->connectionResolver->connection()->rollBack();

            throw new RuntimeException("Oops, something went wrong", 0, $exception);
        }

        return $response;
    }
}
