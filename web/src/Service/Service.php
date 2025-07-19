<?php

declare(strict_types=1);

namespace App\Service;

use PDO;

abstract readonly class Service
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
