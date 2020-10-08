<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class CommonCreateUserService
 * @package App\Services
 */
class CommonCreateUserService implements CreateUserService
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
        $user = new User();
        $user->setAttribute('email', $email);
        $user->setAttribute('password', $this->hasher->make($password));
        $user->save();
    }
}
