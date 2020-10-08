<?php

declare(strict_types=1);

namespace App\Aspects;

use App\JoinPoints\JoinPoint;
use Closure;
use Psr\Log\LoggerInterface;

/**
 * Class PipeLoggingAspect
 * @package App\Aspects
 */
class PipeLoggingAspect
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * PipeLoggingAspect constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param JoinPoint $joinPoint
     * @param Closure $closure
     * @return mixed
     */
    public function handle(JoinPoint $joinPoint, Closure $closure)
    {
        $response = $closure($joinPoint);

        [$email] = $joinPoint->getArguments();

        $this->logger->info("Created User: {$email}");

        return $response;
    }
}
