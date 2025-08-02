<?php

declare(strict_types=1);

namespace App\Controller;

use App\Router\Method\GET;
use App\Router\Path;

#[Path('/order')]
readonly class OrderController extends ProtectedController
{
    #[GET]
    public function viewOrders(): void
    {
        echo $this->render('orders.php');
    }

    /**
     * @param string $id
     * @return void
     */
    #[GET]
    #[Path('/{id}')]
    public function viewOrderDetails(string $id): void
    {
        echo $this->render('order.php');
    }
}
