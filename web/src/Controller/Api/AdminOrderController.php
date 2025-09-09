<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/api/orderList')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL, redirect: false)]
readonly class AdminOrderController extends ApiController
{

}
