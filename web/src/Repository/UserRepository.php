<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\User;

readonly class UserRepository extends Repository
{
    public function insert(User $user): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO user(username, email, hashed_password, role, is_verified)
            VALUE (:username, :email, :hashed_password, :role, :is_verified);
        ');
        $stmt->bindValue(':username', $user->username);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':hashed_password', $user->hashedPassword);
        $stmt->bindValue(':role', $user->role->name);
        $stmt->bindValue(':is_verified', $user->isVerified);
        $stmt->execute();
    }

    public function update(User $user): void{

        $stmt = $this->conn->prepare('
        UPDATE user
        SET
            email = :email,
            hashed_password = :hashed_password,
            role = :role,
            is_verified = :is_verified
        WHERE username = :username;
        ');
        $stmt->bindValue(':username', $user->username);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':hashed_password', $user->hashedPassword);
        $stmt->bindValue(':role', $user->role->name);
        $stmt->bindValue(':is_verified', $user->isVerified);
        $stmt->execute();
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->conn->prepare('
        DELETE FROM user
        WHERE id = :id;
        ');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
