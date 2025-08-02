<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Path;

#[Path('/api/wishlist')]
readonly class WishlistController extends ApiController
{
    #[POST]
    public function addItem(): void
    {
    }

    #[DELETE]
    public function removeItem(): void
    {
    }
}
