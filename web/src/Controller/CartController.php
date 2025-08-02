<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/cart')]
readonly class CartController extends Controller
{
    #[GET]
    public function viewCart(): void
    {
        echo $this->render('cart.php');
    }
}
