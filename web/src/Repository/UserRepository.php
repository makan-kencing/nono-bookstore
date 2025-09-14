<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
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

    public function update(User $user): void
    {
        $stmt = $this->conn->prepare('
        UPDATE user
        SET
            username = :username,
            email = :email,
            hashed_password = :hashed_password,
            role = :role,
            is_verified = :is_verified
        WHERE id = :id;
    ');
        $stmt->bindValue(':id', $user->id, \PDO::PARAM_INT);
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

    public function setProfileImage(int $user_id, int $file_id): void
    {
        $stmt = $this->conn->prepare('
            UPDATE user
            SET image_id = :file_id
            WHERE id = :id;
        ');
        $stmt->execute([
            ':id' => $user_id,
            ':file_id' => $file_id,
        ]);
    }

    public function toggleBlock(int $userId): void
    {
        $stmt = $this->conn->prepare('
            UPDATE user
            SET is_blocked = !is_blocked
            WHERE id = :user_id
        ');
        $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    public function setTotpSecret(int $userId, ?string $secret): void
    {
        $stmt = $this->conn->prepare('
            UPDATE user
            SET totp_secret = :secret
            WHERE id = :user_id
        ');
        $stmt->execute([
            ':user_id' => $userId,
            ':secret' => $secret
        ]);
    }
}
