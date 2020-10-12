<?php

declare(strict_types=1);

namespace Tests\Unit\Aspects;

use App\Aspects\PipeLoggingAspect;
use App\JoinPoints\JoinPoint;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class PipeLoggingTransactionAspectTest
 * @package Tests\Unit\Aspects
 */
class PipeLoggingAspectTest extends TestCase
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
    public function handle_can_write_log(): void
    {
        $email = $this->faker->email;

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('info')
            ->with("Created User: {$email}");

        $aspect = new PipeLoggingAspect($logger);
        $joinPoint = $this->createMock(JoinPoint::class);
        $joinPoint->expects($this->once())
            ->method('getArguments')
            ->willReturn([$email]);

        $aspect->handle($joinPoint, function () {});
    }
}
