<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\ProtectedController;
use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Path;

#[Path('/api/wishlist')]
readonly class WishlistController extends ProtectedController
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
