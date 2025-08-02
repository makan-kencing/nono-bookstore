<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Router\Method\POST;
use App\Router\Path;

#[Path('/api/checkout')]
readonly class CheckoutController extends ApiController
{
    #[POST]
    public function placeOrder(): void
    {
    }
}
