<?php

namespace App\Controller\Web;

use App\Core\View;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderCriteria;
use App\Repository\Query\OrderQuery;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;

#[Path('/order')]
#[RequireAuth]
readonly class OrderController extends WebController
{
    private OrderRepository $orderRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderRepository = new OrderRepository($this->pdo);
    }

    /**
     * @param string $id
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    #[GET]
    #[Path('/{id}')]
    public function viewOrderDetails(string $id): void
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $qb = OrderQuery::orderListings();
        $qb->where(OrderCriteria::byId(alias: 'o'))
            ->bind(':id', $id);
        $order = $this->orderRepository->getOne($qb);

        if ($order === null)
            throw new NotFoundException();

        if ($order->user->id !== $context->id)
            throw new ForbiddenException();

        echo $this->render(
            'webstore/order.php',
            ['order' => $order]
        );
    }
}
