<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class DirtyCreateUserService
 * @package App\Services
 */
class DirtyCreateUserService implements CreateUserService
{
    /**
     * @var Hasher
     */
    private Hasher $hasher;

    /**
     * DirtyCreateUserService constructor.
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     */
    public function run(string $email, string $password): void
    {
        DB::transaction(function () use ($email, $password) {
            $user = new User();
            $user->setAttribute('email', $email);
            $user->setAttribute('password', $this->hasher->make($password));
            $user->save();
        });

        Log::info("Created User: {$email}");
    }
}
