<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/api/rating')]
#[RequireAuth([UserRole::USER], rule: AuthRule::HIGHER)]
readonly class RatingController extends ApiController
{
    #[POST]
    public function submitRating(): void
    {
        header('Content-Type: application/json');
    }

    #[PUT]
    public function editRating(): void
    {
        header('Content-Type: application/json');
    }

    #[DELETE]
    public function deleteRating(): void
    {
        header('Content-Type: application/json');
    }
}
