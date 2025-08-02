<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/checkout')]
readonly class CheckoutController extends WebController
{
    #[GET]
    public function viewCheckout(): void
    {
        echo $this->render('checkout.php');
    }
}
