<?php

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Core\View;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderQuery;
use App\Router\Method\GET;
use App\Router\Path;
use PDO;

#[Path('/admin/orders')]
//#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class OrdersController extends WebController
{
    private OrderRepository $orderRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderRepository = new OrderRepository($this->pdo);
    }
    #[GET]
    public function viewOrders(): void
    {
        $qb = OrderQuery::orderListings();
        $orders= $this->orderRepository->get($qb);
        echo $this->render(
            'admin/orders.php',
            ['orders' => $orders]
        );
    }




}
