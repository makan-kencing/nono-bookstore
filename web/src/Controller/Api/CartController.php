<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\DELETE;
use App\Router\Method\PATCH;
use App\Router\Path;

#[Path('/api/cart')]
readonly class CartController extends ApiController
{
    #[PATCH]
    public function addOrSubtractItem(): void
    {
    }

    #[DELETE]
    public function removeItem(): void
    {
    }
}
