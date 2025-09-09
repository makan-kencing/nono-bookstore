<?php

namespace App\Repository;

use App\Entity\Order\Shipment;
use App\Repository\Repository;
use PDO;

readonly class ShipmentRepository extends Repository
{
    public function insert(Shipment $shipment):void{
        $stmt=$this->conn->prepare('
        INSERT INTO shipment(order_id, ready_at, shipped_at, arrived_at, updated_at)
        VALUES(:order_id, :ready_at, :shipped_at, :arrived_at, :updated_at)
        ');
        $stmt->bindValue('order_id',$shipment->order->id);
        $stmt->bindValue('ready_at',$shipment->readyAt);
        $stmt->bindValue('shipped_at',$shipment->shippedAt);
        $stmt->bindValue('arrived_at',$shipment->arrivedAt);
        $stmt->bindValue('update_at',$shipment->updatedAt);
        $stmt->execute();
    }

    public function update(Shipment $shipment):void{
        $stmt=$this->conn->prepare('
        UPDATE shipment
        SET ready_at=:ready_at,
            shipped_at=:shipped_at,
            arrived_at=:arrived_at,
            updated_at=:updated_at
            WHERE id=:id');
        $stmt->bindValue('ready_at',$shipment->readyAt);
        $stmt->bindValue('shipped_at',$shipment->shippedAt);
        $stmt->bindValue('arrived_at',$shipment->arrivedAt);
        $stmt->bindValue('updated_at',$shipment->updatedAt);
        $stmt->bindValue('id',$shipment->id);
        $stmt->execute();
    }

    public function delete(Shipment $shipment):void{
        $stmt=$this->conn->prepare('
        DELETE FROM shipment
        WHERE id=:id');
        $stmt->bindValue('id',$shipment->id);
        $stmt->execute();
    }


}
