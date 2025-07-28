<?php

declare(strict_types=1);

namespace App\Repository;


use App\Entity\User\User;
use App\Repository\Mapper\UserRowMapper;
use App\Repository\Query\Query;
use PDO;

/**
 * @extends Repository<User>
 */
readonly class UserRepository extends Repository
{
    /**
     * @param Query<User> $query
     * @return User[]
     */
    public function get(Query $query): array
    {
        $stmt = $query->createQuery($this->conn);
        $stmt->execute();

        $rowMapper = new UserRowMapper('user.');
        return $rowMapper->map($stmt);
    }

    /**
     * @param Query<int> $query
     * @return int
     */
    public function count(Query $query): int
    {
        $stmt = $query->createQuery($this->conn);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }


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
            username = :username,
            email = :email,
            hashed_password = :hashed_password,
            role = :role,
            is_verified = :is_verified
        WHERE id = :id;
    ');

        $stmt->bindValue(':id', $user->id);
        $stmt->bindValue(':username', $user->username);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':hashed_password', $user->hashedPassword);
        $stmt->bindValue(':role', $user->role->name);
        $stmt->bindValue(':is_verified', $user->isVerified);
        $stmt->execute();
    }



}
