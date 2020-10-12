<?php

declare(strict_types=1);

namespace Tests\Integration\Services;

use App\Models\User;
use App\Services\ProxyCreateUserService;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Tests\TestCase;
use function Functional\some;

/**
 * Class ProxyCreateUserServiceTest
 * @package Tests\Integration\Services
 */
class ProxyCreateUserServiceTest extends TestCase
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
        $service = resolve(ProxyCreateUserService::class);
        $service->run($email, $this->faker->password);

        $this->assertNotNull(User::query()->where('email', $email)->first());
    }

    /**
     * @test
     *
     * @return void
     */
    public function transaction_will_run_automatically(): void
    {
        DB::connection()->setEventDispatcher(Event::fake([
            TransactionBeginning::class
        ]));

        $service = resolve(ProxyCreateUserService::class);
        $service->run($this->faker->email, $this->faker->password);

        Event::assertDispatched(TransactionBeginning::class);
    }

    /**
     * @test
     *
     * @return void
     */
    public function log_will_write_automatically(): void
    {
        Log::setDefaultDriver('testing');

        $email = $this->faker->email;
        $service = resolve(ProxyCreateUserService::class);
        $service->run($email, $this->faker->password);

        /**
         * @var Logger $logger
         */
        $logger = Log::driver()->getLogger();

        /**
         * @var TestHandler $handler
         */
        [$handler] = $logger->getHandlers();

        $this->assertTrue(
            some($handler->getRecords(), fn(array $record) => $record['message'] === "Created User: {$email}")
        );
    }
}
