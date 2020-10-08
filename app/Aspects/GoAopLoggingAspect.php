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
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * GoAopLoggingAspect constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @After("execution(public App\Services\GoAopCreateUserService->run(*))")
     */
    public function afterMethod(MethodInvocation $invocation)
    {
        [$email] = $invocation->getArguments();

        $this->logger->info("Created User: {$email}");
    }
}
