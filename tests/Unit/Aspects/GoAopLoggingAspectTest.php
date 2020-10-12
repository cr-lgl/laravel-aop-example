<?php

declare(strict_types=1);

namespace Tests\Unit\Aspects;

use App\Aspects\AspectPropertyFactory;
use App\Aspects\GoAopLoggingAspect;
use Faker\Factory;
use Faker\Generator;
use Go\Aop\Intercept\MethodInvocation;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class GoAopLoggingAspectTest
 * @package Tests\Unit\Aspects
 */
class GoAopLoggingAspectTest extends TestCase
{
    /**
     * @var Generator $faker
     */
    private Generator $faker;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    /**
     * @test
     *
     * @return void
     */
    public function after_method_can_write_log(): void
    {
        $email = $this->faker->email;

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with("Created User: {$email}");

        $factory = $this->createMock(AspectPropertyFactory::class);
        $factory->expects($this->once())
            ->method('make')
            ->willReturn($logger);

        $aspect = new GoAopLoggingAspect($factory);
        $invocation = $this->createMock(MethodInvocation::class);
        $invocation->expects($this->once())
            ->method('getArguments')
            ->willReturn([$email]);

        $aspect->afterMethod($invocation);
    }
}
