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
     * @var AspectPropertyFactory
     */
    private AspectPropertyFactory $propertyFactory;

    /**
     * GoAopTransactionAspect constructor.
     * @param AspectPropertyFactory $propertyFactory
     */
    public function __construct(AspectPropertyFactory $propertyFactory)
    {
        $this->propertyFactory = $propertyFactory;
    }

    /**
     * @param MethodInvocation $invocation
     * @return mixed
     *
     * @Around("@execution(App\Annotations\Transactional)")
     */
    public function aroundTransactional(MethodInvocation $invocation)
    {
        /**
         * @var ConnectionResolverInterface $connectionResolver
         */
        $connectionResolver = $this->propertyFactory->make(ConnectionResolverInterface::class);

        $connectionResolver->connection()->beginTransaction();

        try {
            $response = $invocation->proceed();

            $connectionResolver->connection()->commit();
        } catch (Throwable $exception) {
            $connectionResolver->connection()->rollBack();

            throw new RuntimeException('Oops, something went wrong', 0, $exception);
        }

        return $response;
    }
}
