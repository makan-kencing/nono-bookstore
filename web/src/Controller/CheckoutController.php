<?php

declare(strict_types=1);

namespace App\Controller;

readonly class CheckoutController extends ProtectedController
{
    public function viewCheckout(): void
    {
        echo $this->render('checkout.php');
    }

    public function placeOrder(): void
    {
    }
}
