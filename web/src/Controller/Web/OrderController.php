<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\User\UserRole;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;

#[Path('/order')]
#[RequireAuth([UserRole::USER], rule: AuthRule::HIGHER)]
readonly class OrderController extends WebController
{
    #[GET]
    public function viewOrders(): void
    {
        echo $this->render('webstore/orders.php');
    }

    /**
     * @param string $id
     * @return void
     */
    #[GET]
    #[Path('/{id}')]
    public function viewOrderDetails(string $id): void
    {
        echo $this->render('webstore/order.php');
    }
}
