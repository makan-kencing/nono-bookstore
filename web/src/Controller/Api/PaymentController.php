<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\ProtectedController;
use App\Router\Method\POST;
use App\Router\Path;

#[Path('/api/payment')]
readonly class PaymentController extends ProtectedController
{
    #[POST]
    public function completePayment(): void
    {
    }
}
