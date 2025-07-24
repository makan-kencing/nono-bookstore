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

        $rowMapper = new UserRowMapper();
        return $rowMapper->map($stmt, prefix: 'user.');
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
    }

}
