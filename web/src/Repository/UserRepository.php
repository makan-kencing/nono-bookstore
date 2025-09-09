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

    public function update(User $user): void
    {

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

    /**
     * Dynamic update for user or user_profile table
     */
    public function updateUser(int $userId, array $fields, string $table = 'user'): void
    {
        if (empty($fields)) {
            return;
        }

        // Build SET part dynamically
        $setParts = [];
        $params = ['id' => $userId];
        foreach ($fields as $column => $value) {
            $setParts[] = "$column = :$column";
            $params[$column] = $value;
        }

        $setClause = implode(", ", $setParts);

        // Choose correct ID column
        $idColumn = ($table === 'user') ? 'id' : 'user_id';

        $sql = "UPDATE $table SET $setClause WHERE $idColumn = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Convenience wrappers
     */
    public function updateAvatar(int $userId, string $avatarPath): void
    {
        $this->updateUser($userId, ['image_id' => $avatarPath], 'user');
    }

    public function updateUsername(int $userId, string $username): void
    {
        $this->updateUser($userId, ['username' => $username], 'user');
    }

    public function updateEmail(int $userId, string $email): void
    {
        $this->updateUser($userId, ['email' => $email], 'user');
    }

    public function updateContact(int $userId, string $contact): void
    {
        $this->updateUser($userId, ['contact_no' => $contact], 'user_profile');
    }

    public function updateDob(int $userId, string $dob): void
    {
        $this->updateUser($userId, ['dob' => $dob], 'user_profile');
    }

    public function updatePassword(int $userId, string $hashedPassword): void
    {
        $this->updateUser($userId, ['hashed_password' => $hashedPassword], 'user');
    }
}
