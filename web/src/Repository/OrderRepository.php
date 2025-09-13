<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Response\CategorySalesDTO;
use App\DTO\Response\MonthlySalesDTO;
use App\Entity\Order\Invoice;
use App\Entity\Order\Order;
use App\Entity\Order\OrderAdjustment;
use App\Entity\Order\OrderItem;
use App\Entity\Order\Payment;
use App\Entity\Order\Shipment;
use DateTime;
use UnexpectedValueException;

readonly class OrderRepository extends Repository
{
    public function createCheckout(Order $order): void
    {
        $this->insert($order);
        foreach ($order->items as $item)
            $this->insertItem($item);
        foreach ($order->adjustments as $adjustment)
            $this->insertAdjustment($adjustment);

        $this->insertInvoice($order->invoice ?? throw new UnexpectedValueException());
        $this->insertPayment($order->invoice->payment ?? throw new UnexpectedValueException());
        $this->insertShipment($order->shipment ?? throw new UnexpectedValueException());
    }


    public function insert(Order $order): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO `order` (user_id, address_id, ref_no, ordered_at)
            VALUES (:user_id, :address_id, :ref_no, :ordered_at)
        ');
        $stmt->execute([
            ':user_id' => $order->user->id,
            ':address_id' => $order->address->id,
            ':ref_no' => $order->refNo,
            ':ordered_at' => $order->orderedAt->format( 'Y-m-d H:i:s')
        ]);

        $order->id = (int) $this->conn->lastInsertId();
    }

    public function insertAdjustment(OrderAdjustment $orderAdjustment): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO order_adjustment (order_id, type, amount, comment)
            VALUES (:order_id, :type, :amount, :comment)
        ');
        $stmt->execute([
            ':order_id' => $orderAdjustment->order->id,
            ':type' => $orderAdjustment->type->name,
            ':amount' => $orderAdjustment->amount,
            ':comment' => $orderAdjustment->comment
        ]);

        $orderAdjustment->id = (int) $this->conn->lastInsertId();
    }

    public function insertItem(OrderItem $orderItem): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO order_item (order_id, book_id, quantity, unit_price, comment)
            VALUES (:order_id, :book_id, :quantity, :unit_price, :comment)
        ');
        $stmt->execute([
            ':order_id' => $orderItem->order->id,
            ':book_id' => $orderItem->book->id,
            ':quantity' => $orderItem->quantity,
            ':unit_price' => $orderItem->book->getCurrentPrice()->amount
                ?? throw new UnexpectedValueException('Book ' . $orderItem->book->id . ' does not have price'),
            ':comment' => $orderItem->comment
        ]);
    }

    public function insertShipment(Shipment $shipment): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO shipment (order_id, ready_at, shipped_at, arrived_at)
            VALUES (:order_id, :ready_at, :shipped_at, :arrived_at)
        ');
        $stmt->execute([
            ':order_id' => $shipment->order->id,
            ':ready_at' => $shipment->readyAt?->format( 'Y-m-d H:i:s'),
            ':shipped_at' => $shipment->shippedAt?->format( 'Y-m-d H:i:s'),
            ':arrived_at' => $shipment->arrivedAt?->format( 'Y-m-d H:i:s')
        ]);

        $shipment->id = (int) $this->conn->lastInsertId();
    }

    public function insertPayment(Payment $payment): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO payment (invoice_id, ref_no, method, amount, paid_at)
            VALUES (:invoice_id, :ref_no, :method, :amount, :paid_at)
        ');
        $stmt->execute([
            ':invoice_id' => $payment->invoice->id,
            ':ref_no' => $payment->refNo,
            ':method' => $payment->method,
            ':amount' => $payment->amount,
            ':paid_at' => $payment->paidAt->format( 'Y-m-d H:i:s'),
        ]);

        $payment->id = (int) $this->conn->lastInsertId();
    }

    public function insertInvoice(Invoice $invoice): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO invoice (order_id, invoiced_at)
            VALUES (:order_id, :invoiced_at)
        ');
        $stmt->execute([
            ':order_id' => $invoice->order->id,
            ':invoiced_at' => $invoice->invoicedAt->format( 'Y-m-d H:i:s')
        ]);

        $invoice->id = (int) $this->conn->lastInsertId();
    }

    public function updateShipment(Shipment $shipment): void{
        $stmt = $this->conn->prepare('
        UPDATE `shipment`
        SET `ready_at` = :ready_at,
            `shipped_at` = :shipped_at,
            `arrived_at` = :arrived_at
            WHERE `id` = :id'
        );
        $stmt->execute([
            ':id' => $shipment->id,
            ':ready_at' => $shipment->readyAt?->format( 'Y-m-d H:i:s'),
            ':shipped_at' => $shipment->shippedAt?->format( 'Y-m-d H:i:s'),
            ':arrived_at' => $shipment->arrivedAt?->format( 'Y-m-d H:i:s')
        ]);
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return MonthlySalesDTO[]
     */
    public function getMonthlySales(DateTime $from, DateTime $to): array
    {
        $stmt = $this->conn->prepare('
        select extract(year_month from o.ordered_at) `yearMonth`,
               SUM(oi.quantity)                      `quantitySold`,
               SUM(oi.unit_price)                    `revenue`
        from `order` o
                 join order_item oi on o.id = oi.order_id
        where o.ordered_at BETWEEN :start_at AND :end_at
        group by 1;
        ');
        $stmt->execute([
            ':start_at' => $from->format('Y-m-d'),
            ':end_at' => $to->format('Y-m-d')
        ]);

        $results = [];
        foreach ($stmt as $row)
            $results[] = new MonthlySalesDTO(
                (string) $row['yearMonth'],
                (int) $row['quantitySold'],
                (int) $row['revenue']
            );
        return $results;
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return CategorySalesDTO[]
     */
    public function getCategorySales(DateTime $from, DateTime $to): array
    {
        $stmt = $this->conn->prepare('
        select c.name            `categoryName`,
               SUM(oi.quantity)  `quantitySold`,
               SUM(oi.unit_price)`revenue`
        from `order` o
                 join order_item oi on o.id = oi.order_id
                 join book b on oi.book_id = b.id
                 join work w on b.work_id = w.id
                 join category_definition cd on w.id = cd.work_id and cd.is_primary is true
                 join category c on cd.category_id = c.id
        where o.ordered_at BETWEEN :start_at AND :end_at
        group by 1;
        ');
        $stmt->execute([
            ':start_at' => $from->format('Y-m-d'),
            ':end_at' => $to->format('Y-m-d')
        ]);

        $results = [];
        foreach ($stmt as $row)
            $results[] = new CategorySalesDTO(
                (string) $row['categoryName'],
                (int) $row['quantitySold'],
                (int) $row['revenue']
            );
        return $results;
    }
}
