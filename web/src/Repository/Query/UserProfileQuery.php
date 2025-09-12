<?php

declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\User\User;
use App\Entity\User\UserProfile;
use App\Orm\QueryBuilder;

class UserProfileQuery
{
    /**
     * @return QueryBuilder
     */
    public static function withMinimalDetails(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(UserProfile::class, 'up')
            ->join('user', 'u');

        return $qb;
    }
}
