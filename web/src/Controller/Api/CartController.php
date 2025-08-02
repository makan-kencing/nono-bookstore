<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\DELETE;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;

#[Path('/api/cart')]
readonly class CartController extends ApiController
{
    #[POST]
    public function addItem(): void
    {
    }

    #[PUT]
    public function subtractItem(): void
    {
    }

    #[DELETE]
    public function removeItem(): void
    {
    }
}
