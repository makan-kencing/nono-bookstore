<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\UnauthorizedException;
use App\Orm\Expr\OrderDirection;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderCriteria;
use App\Repository\Query\OrderQuery;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/orders')]
#[RequireAuth]
readonly class OrdersController extends WebController
{
    private OrderRepository $orderRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderRepository = new OrderRepository($this->pdo);
    }

    /**
     * @throws UnauthorizedException
     */
    #[GET]
    public function viewOrders(): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = OrderQuery::orderListings();
        $qb->where(OrderCriteria::byUserId(alias: 'o'))
            ->bind(':user_id', $context->id)
            ->orderBy('o.ordered_at', OrderDirection::DESCENDING);

        $orders = $this->orderRepository->get($qb);

        echo $this->render(
            'webstore/orders.php',
            ['orders' => $orders]
        );
    }

}
