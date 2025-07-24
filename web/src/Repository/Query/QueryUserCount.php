<?php

declare(strict_types=1);

namespace App\Repository\Query;

use PDO;
use PDOStatement;

/**
 * @extends Query<int>
 */
class QueryUserCount extends Query
{
    public ?string $username = null;
    public ?string $email = null;

    public function createQuery(PDO $pdo): PDOStatement
    {
        $query = '
        SELECT count(*)
        FROM user
        ';
        $params = [];

        $query .= 'WHERE 1 = 1';
        if ($this->username != null) {
            $query .= ' AND username = :username';
            $params['username'] = $this->username;
        }
        if ($this->email != null) {
            $query .= ' AND email = :email';
            $params['email'] = $this->email;
        }

        $stmt = $pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt;
    }
}
