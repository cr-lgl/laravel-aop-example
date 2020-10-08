<?php

declare(strict_types=1);

namespace App\Services;

use App\Annotations\Transactional;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Class GoAopCreateUserService
 * @package App\Services
 */
class GoAopCreateUserService implements CreateUserService
{
    /**
     * @var Hasher
     */
    private Hasher $hasher;

    /**
     * GoAopCreateUserService constructor.
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     *
     * @Transactional
     */
    public function run(string $email, string $password): void
    {
        $user = new User();
        $user->setAttribute('email', $email);
        $user->setAttribute('password', $this->hasher->make($password));
        $user->save();
    }
}
