<?php
declare(strict_types=1);

namespace App\Repository\Query;

use App\Entity\User\UserToken;
use App\Orm\QueryBuilder;

class UserTokenQuery
{
    /**
     * @return QueryBuilder<UserToken>
     */
    public static function withMinimalDetails(): QueryBuilder
    {
        $qb = new QueryBuilder();
        $qb->from(UserToken::class, 'ut');

        return $qb;
    }
}
