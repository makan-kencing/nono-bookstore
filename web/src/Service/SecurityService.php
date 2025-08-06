<?php

declare(strict_types=1);

namespace App\Service;

use PDO;
use App\Entity\User\User;
use App\Repository\UserRepository;
use App\Exception\ValidationException;
use App\Exception\UniqueConstraintException;

readonly class SecurityService extends Service
{
    public function register(): void
    {
        // Check required fields

        // Validate password length only

        // Check uniqueness

        // Hash password and store user
    }
}
