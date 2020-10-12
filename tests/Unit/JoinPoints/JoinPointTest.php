<?php

declare(strict_types=1);

namespace Tests\Unit\JoinPoints;

use App\JoinPoints\JoinPoint;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class JoinPointTest
 * @package Tests\Unit\JoinPoints
 */
class JoinPointTest extends TestCase
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
    public function get_arguments_will_return_construct_args(): void
    {
        $key = 'email';
        $email = $this->faker->email;
        $joinPoint = new JoinPoint(new User(), 'setAttribute', $key, $email);

        $this->assertEquals([$key, $email], $joinPoint->getArguments());
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_class_name_will_return_instance_class(): void
    {
        $joinPoint = new JoinPoint(new User(), 'setAttribute', 'email', $this->faker->email);

        $this->assertEquals(User::class, $joinPoint->getClassName());
    }

    /**
     * @test
     *
     * @return void
     */
    public function get_get_signature_will_return_construct_method(): void
    {
        $method = 'setAttribute';
        $joinPoint = new JoinPoint(new User(), $method, 'email', $this->faker->email);

        $this->assertEquals($method, $joinPoint->getSignature());
    }

    /**
     * @test
     *
     * @return void
     */
    public function proceed_can_call_only_once(): void
    {
        $joinPoint = new JoinPoint(new User(), 'setAttribute', 'email', $this->faker->email);

        $joinPoint->proceed();

        $this->assertTrue($joinPoint->proceedCalled());
    }

    /**
     * @test
     *
     * @return void
     */
    public function proceed_will_return_method_called_result(): void
    {
        $key = 'email';
        $email = $this->faker->email;
        $joinPoint = new JoinPoint(new User(), 'setAttribute', $key, $email);

        /**
         * @var User $result
         */
        $result = $joinPoint->proceed();

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($email, $result->getAttribute($key));
    }
}
