<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;

#[Path('/api/rating')]
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
