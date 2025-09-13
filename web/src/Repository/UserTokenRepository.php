<?php
declare(strict_types=1);

namespace App\Repository;

use DateTime;
use PDO;

readonly class UserTokenRepository extends Repository
{
    public function insertToken(int $userId, string $type, string $selector, string $hashedToken, DateTime $expiresAt): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO user_token(user_id, type, selector, token, expires_at)
            VALUES (:user_id, :type, :selector, :token, :expires_at)
        ');
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':selector', $selector);
        $stmt->bindValue(':token', $hashedToken);
        $stmt->bindValue(':expires_at', $expiresAt->format('Y-m-d H:i:s'));
        $stmt->execute();
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteByUserAndType(int $userId, string $type): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE user_id = :user_id AND type = :type');
        $stmt->execute([':user_id' => $userId, ':type' => $type]);
    }

    public function cleanupExpired(): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE expires_at < NOW()');
        $stmt->execute();
    }
}
