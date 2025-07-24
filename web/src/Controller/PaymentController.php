<?php

declare(strict_types=1);

namespace App\Controller;

readonly class PaymentController extends Controller
{
    public function viewPayment(): void
    {
        echo $this->render('payment.php');
    }

    public function completePayment(): void
    {
    }
}
