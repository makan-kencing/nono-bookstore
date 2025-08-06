<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/checkout')]
#[RequireAuth([UserRole::USER], rule: AuthRule::HIGHER)]
readonly class CheckoutController extends WebController
{
    #[GET]
    public function viewCheckout(): void
    {
        echo $this->render('webstore/checkout.php');
    }
}
