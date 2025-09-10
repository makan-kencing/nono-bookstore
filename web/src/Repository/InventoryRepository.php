<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Inventory;
use PDOException;

readonly class InventoryRepository extends Repository
{
    public function insert(Inventory $inventory): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO inventory (book_id, location, quantity)
            VALUES (:book_id, :location, :quantity)
        ');
        $stmt->execute([
            ':book_id' => $inventory->book->id,
            ':location' => $inventory->location->name,
            ':quantity' => $inventory->quantity
        ]);
    }

    public function update(Inventory $inventory): void
    {
        $stmt = $this->conn->prepare('
            UPDATE inventory
            SET quantity = :quantity
            WHERE id = :id
        ');
        $stmt->execute([
            ':quantity' => $inventory->quantity,
            ':id' => $inventory->id
        ]);
    }

    public function upsert(Inventory $inventory): void
    {
        try {
            $this->insert($inventory);
        } catch (PDOException) {
            $this->update($inventory);
        }
    }
}
