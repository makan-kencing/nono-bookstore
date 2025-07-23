<?php

declare(strict_types=1);

namespace App\Repository\Query;

use PDO;
use PDOStatement;

/**
 * @template T
 */
abstract class Query
{
    abstract public function createQuery(PDO $pdo): PDOStatement;
}
