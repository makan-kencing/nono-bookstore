<?php

namespace App\Repository;

use PDO;

abstract readonly class Repository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }
}
