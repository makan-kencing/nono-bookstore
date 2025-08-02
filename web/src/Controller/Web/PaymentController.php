<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/payment')]
readonly class PaymentController extends WebController
{
    #[GET]
    public function viewPayment(): void
    {
        echo $this->render('payment.php');
    }
}
