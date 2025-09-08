<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserLoginContextDTO;
use PDO;

abstract readonly class Service
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSessionContext(): ?UserLoginContextDTO
    {
        return AuthService::getLoginContext();
    }
}
