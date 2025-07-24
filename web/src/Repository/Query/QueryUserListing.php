<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\User\User;
use PDO;
use PDOStatement;

/**
 * @extends Query<User>
 */
class QueryUserListing extends Query
{
    public function createQuery(PDO $pdo): PDOStatement
    {
        $stmt = $pdo->prepare('
            select u.id              `user.id`,
                   u.username        `user.username`,
                   u.email           `user.email`,
                   u.hashed_password `user.hashedPassword`,
                   u.role            `user.role`,
                   u.is_verified     `user.isVerified`,
                   up.user_id        `user.profile.id`,
                   up.contact_no     `user.profile.contactNo`,
                   up.dob            `user.profile.dob`
            from user u
                     left join user_profile up on u.id = up.user_id;
        ');
        return $stmt;
    }
}
