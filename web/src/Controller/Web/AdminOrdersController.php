<?php

namespace App\Controller\Web;

use App\Core\View;
use App\Entity\User\UserRole;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderQuery;
use App\Repository\UserRepository;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/admin/orders')]
//#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class AdminOrdersController extends WebController
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

    #[GET]
    #[Path('/{id}')]
    public function viewOrder(int $id): void
    {}


}
