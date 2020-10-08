<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Interface CreateUserService
 * @package App\Services
 */
interface CreateUserService
{
    /**
     * @param string $email
     * @param string $password
     * @return void
     */
    public function run(string $email, string $password): void;
}
