<?php

namespace App\Service;

use App\DTO\Request\SearchDTO;
use App\DTO\Response\CategorySalesDTO;
use App\DTO\Response\MonthlySalesDTO;
use App\DTO\Response\PageResultDTO;
use App\Entity\Order\Order;
use App\Entity\User\User;
use App\Exception\NotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\OrderRepository;
use App\Repository\Query\OrderCriteria;
use App\Repository\Query\OrderQuery;
use DateTime;
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

    /**
     * @throws UnauthorizedException
     * @throws NotFoundException
     */
    public function updateShipment(int $id):void
    {
        $context = $this->getSessionContext();
        if ($context===null)
            throw new UnauthorizedException();

        $qb= OrderQuery::withMinimalDetails();
        $qb->join('shipment', 's')
            ->where(OrderCriteria::byId(alias: 'o'))
            ->bind(':id', $id);

        $order = $this->orderRepository->getOne($qb);
        if($order === null)
            throw new NotFoundException();

        if ($order->shipment->readyAt==null){
            $order->shipment->readyAt=  new \DateTime();
        }
        elseif ($order->shipment->shippedAt==null){
            $order->shipment->shippedAt=  new \DateTime();
        }
        elseif ($order->shipment->arrivedAt==null){
            $order->shipment->arrivedAt=  new \DateTime();
        }
        else{
            return;
        }

        $this->orderRepository->updateShipment($order->shipment);
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return array{0: MonthlySalesDTO[], 0: CategorySalesDTO[]}
     */
    public function getSalesMetrics(DateTime $from, DateTime $to): array
    {
        return [
            $this->orderRepository->getMonthlySales($from, $to),
            $this->orderRepository->getCategorySales($from, $to)
        ];
    }

    /**
     * @param SearchDTO $dto
     * @return PageResultDTO<User>
     */
    public function search(SearchDTO $dto): PageResultDTO
    {
        $qb = OrderQuery::orderListings();

        if ($dto->query)
            $qb->where(OrderCriteria::byId(alias: 'o'))
                ->bind(':id', (int)$dto->query);

        $qb->page($dto->toPageRequest());

        $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        try {
            return new PageResultDTO(
                $this->orderRepository->get($qb),
                $this->orderRepository->count($qb),
                $dto->toPageRequest()
            );
        } finally {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
    }
}
