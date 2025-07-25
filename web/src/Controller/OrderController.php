<?php

declare(strict_types=1);

namespace App\Controller;

readonly class OrderController extends ProtectedController
{
    public function viewOrders(): void
    {
        echo $this->render('orders.php');
    }

    /**
     * @param array<string, string> $pathVars
     * @return void
     */
    public function viewOrderDetails(array $pathVars): void
    {
        $id = (int)$pathVars['id'];

        echo $this->render('order.php');
    }
}
