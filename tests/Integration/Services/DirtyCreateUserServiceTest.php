<?php

declare(strict_types=1);

namespace Tests\Integration\Services;

use App\Models\User;
use App\Services\DirtyCreateUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class DirtyCreateUserServiceTest
 * @package Tests\Integration\Services
 */
class DirtyCreateUserServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     *
     * @return void
     */
    public function can_create_a_user(): void
    {
        $email = $this->faker->email;
        $service = resolve(DirtyCreateUserService::class);
        $service->run($email, $this->faker->password);

        $this->assertNotNull(User::query()->where('email', $email)->first());
    }
}
