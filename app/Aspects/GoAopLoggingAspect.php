<?php

declare(strict_types=1);

namespace App\Aspects;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Psr\Log\LoggerInterface;

/**
 * Class GoAopLoggingAspect
 * @package App\Aspects
 */
class GoAopLoggingAspect implements Aspect
{
    /**
     * @var AspectPropertyFactory
     */
    private AspectPropertyFactory $propertyFactory;

    /**
     * GoAopLoggingAspect constructor.
     * @param AspectPropertyFactory $propertyFactory
     */
    public function __construct(AspectPropertyFactory $propertyFactory)
    {
        $this->propertyFactory = $propertyFactory;
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @After("execution(public App\Services\GoAopCreateUserService->run(*))")
     */
    public function afterMethod(MethodInvocation $invocation)
    {
        /**
         * @var LoggerInterface $logger
         */
        $logger = $this->propertyFactory->make(LoggerInterface::class);

        [$email] = $invocation->getArguments();

        $logger->info("Created User: {$email}");
    }
}
