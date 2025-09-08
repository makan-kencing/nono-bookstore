<?php

namespace App\Repository;

use App\Entity\Order\Order;
use App\Repository\Repository;

readonly class OrderRepository extends Repository
{
    public function insert(Order $order): void{
        $stmt=$this->conn->prepare('
            INSERT INTO `order`(user_id, address_id, ref_no,ordered_at)
            VALUES(:user_id, :address_id, :ref_no, :ordered_at)
            ');
            $stmt->bindValue(':user_id',$order->user->id);
            $stmt->bindValue(':address_id',$order->shipment);
            $stmt->bindValue(':ref_no',$order->refNo);
            $stmt->bindValue(':ordered_at',$order->orderedAt);
            $stmt->execute();
    }

    public function edit(Order $order): void{
        $stmt = $this->conn->prepare('
        UPDATE `order`
        SET
            ref_no = :ref_no,
            ordered_at = :ordered_at
        WHERE id = :order_id;
        ');
        $stmt->bindValue(':ref_no',$order->refNo);
        $stmt->bindValue(':order_id',$order->id);
        $stmt->bindValue(':ordered_at',$order->orderedAt);
        $stmt->execute();
    }

    public function delete(Order $order): void{
        $stmt = $this->conn->prepare('
        DELETE FROM `order`
        WHERE id = :order_id;');
        $stmt->bindValue(':order_id',$order->id);
        $stmt->execute();

    }
}
