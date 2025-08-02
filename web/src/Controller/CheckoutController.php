<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/checkout')]
readonly class CheckoutController extends ProtectedController
{
    #[GET]
    public function viewCheckout(): void
    {
        echo $this->render('checkout.php');
    }
}
