<?php

declare(strict_types=1);

namespace App\Controller;

readonly class CartController extends Controller
{
    public function viewCart(): void
    {
        echo $this->render('cart.php');
    }

    public function addItem(): void
    {
    }

    public function subtractItem(): void
    {
    }

    public function removeItem(): void
    {
    }
}
