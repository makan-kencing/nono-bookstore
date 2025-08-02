<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/cart')]
readonly class CartController extends WebController
{
    #[GET]
    public function viewCart(): void
    {
        echo $this->render('cart.php');
    }
}
