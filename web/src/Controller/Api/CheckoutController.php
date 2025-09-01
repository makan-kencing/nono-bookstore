<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/api/checkout')]
#[RequireAuth(redirect: false)]
readonly class CheckoutController extends ApiController
{
    #[POST]
    public function placeOrder(): void
    {
    }
}
