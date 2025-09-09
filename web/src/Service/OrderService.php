<?php

namespace App\Service;

use App\Entity\Order\Order;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderQuery;
use App\Service\Service;
use PDO;

readonly class OrderService extends Service
{
    private OrderRepository $orderRepository;
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->orderRepository = new OrderRepository($this->pdo);
    }

    public function viewOrderList(): array
    {
        $qb=OrderQuery::orderListings();
        return $this->orderRepository->get($qb);
    }

    public function getOrderDetails(string $id): ?Order
    {
          $qb=OrderQuery::orderListings()
              ->where($id);
        return $this->orderRepository->getOne($qb);
    }

    public function update()
    {

    }




}
