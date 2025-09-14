<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\UserToken;
use App\Entity\User\UserTokenType;
use PDO;

readonly class UserTokenRepository extends Repository
{
    public function createToken(UserToken $token): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO user_token(user_id, type, selector, token, expires_at)
            VALUES (:user_id, :type, :selector, :token, :expires_at)
        ');
        $stmt->execute([
            ':user_id' => $token->user->id,
            ':type' => $token->type->name,
            ':selector' => $token->selector,
            ':token' => $token->token,
            ':expires_at' => $token->expiresAt->format('Y-m-d H:i:s')
        ]);

        $token->id = (int) $this->conn->lastInsertId();
    }

    public function deleteById(int $id): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteByUserAndType(int $userId, UserTokenType $type): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE user_id = :user_id AND type = :type');
        $stmt->execute([':user_id' => $userId, ':type' => $type->name]);
    }

    public function cleanupExpired(): void
    {
        $stmt = $this->conn->prepare('DELETE FROM user_token WHERE expires_at < NOW()');
        $stmt->execute();
    }
}
