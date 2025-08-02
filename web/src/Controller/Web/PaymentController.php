<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/payment')]
#[RequireAuth([UserRole::USER], rule: AuthRule::HIGHER)]
readonly class PaymentController extends WebController
{
    #[GET]
    public function viewPayment(): void
    {
        echo $this->render('payment.php');
    }
}
