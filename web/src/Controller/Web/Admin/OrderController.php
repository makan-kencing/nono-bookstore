<?php

namespace App\Controller\Web\Admin;

use App\Controller\Web\WebController;
use App\Core\View;
use App\Entity\User\UserRole;
use App\Exception\NotFoundException;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderCriteria;
use App\Repository\Query\OrderQuery;
use App\Router\AuthRule;
use App\Router\Method\GET;
use App\Router\Path;
use App\Router\RequireAuth;
use PDO;


#[Path('/admin/order')]
#[RequireAuth([UserRole::STAFF], rule: AuthRule::HIGHER_OR_EQUAL)]
readonly class OrderController extends WebController
{
    private OrderRepository $orderRepository;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->orderRepository = new OrderRepository($this->pdo);
    }

    /**
     * @throws NotFoundException
     */
    #[GET]
    #[Path('/{id}')]
    public function viewOrder(string $id): void
    {
        $qb =OrderQuery::orderListings()
            ->where(OrderCriteria::byId(alias:'o'))
            ->bind(':id', $id);
        $order = $this->orderRepository->getOne($qb);

        if ($order === null)
            throw new NotFoundException();

        echo $this->render('admin/order/order.php', ['order' => $order]);
    }


}
