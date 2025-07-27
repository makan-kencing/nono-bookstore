<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ABC\Entity;
use PDO;

/**
 * @template T of Entity
 */
abstract readonly class Repository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

}
