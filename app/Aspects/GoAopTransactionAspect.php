<?php

declare(strict_types=1);

namespace App\Aspects;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use Illuminate\Database\ConnectionResolverInterface;
use RuntimeException;
use Throwable;

/**
 * Class GoAopTransactionAspect
 * @package App\Aspects
 */
class GoAopTransactionAspect implements Aspect
{
    /**
     * @var ConnectionResolverInterface
     */
    private ConnectionResolverInterface $connectionResolver;

    /**
     * GoAopTransactionAspect constructor.
     * @param ConnectionResolverInterface $connectionResolver
     */
    public function __construct(ConnectionResolverInterface $connectionResolver)
    {
        $this->connectionResolver = $connectionResolver;
    }

    /**
     * @param MethodInvocation $invocation
     * @return mixed
     *
     * @Around("@execution(App\Annotations\Transactional)")
     */
    public function aroundTransactional(MethodInvocation $invocation)
    {
        $this->connectionResolver->connection()->beginTransaction();

        try {
            $response = $invocation->proceed();

            $this->connectionResolver->connection()->commit();
        } catch (Throwable $exception) {
            $this->connectionResolver->connection()->rollBack();

            throw new RuntimeException('Oops, something went wrong', 0, $exception);
        }

        return $response;
    }
}
