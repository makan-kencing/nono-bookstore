<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\User\UserRole;
use PDO;
use PDOStatement;

class QueryUserRegister extends Query
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $hashedPassword = null;
    public UserRole $role = UserRole::USER;
    public bool $isVerified = false;

    public function createQuery(PDO $pdo): PDOStatement
    {
        $stmt = $pdo->prepare(
            'INSERT INTO user (username, email, hashed_password, role, is_verified)
             VALUES (:username, :email, :hashedPassword, :role, :isVerified)'
        );

        $params = [
            ':username' => $this->username,
            ':email' => $this->email,
            ':hashedPassword' => $this->hashedPassword,
            ':role' => $this->role,
            ':isVerified' => $this->isVerified,
        ];

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, $key === ':isVerified' ? PDO::PARAM_BOOL : PDO::PARAM_STR);
        }

        return $stmt;
    }
}
