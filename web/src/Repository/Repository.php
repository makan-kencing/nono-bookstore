<?php

namespace App\Repository;

use PDO;

abstract readonly class Repository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
}
