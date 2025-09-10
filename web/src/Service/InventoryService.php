<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book\Book;
use App\Entity\Product\Inventory;
use App\Repository\InventoryRepository;
use PDO;

readonly class InventoryService extends Service
{
    private InventoryRepository $inventoryRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->inventoryRepository = new InventoryRepository($pdo);
    }

    public function deductStocks(Book $book, int $quantity): void
    {
        usort(
            $book->inventories,
            fn(Inventory $o1, Inventory $o2) => $o1->location->compareTo($o2->location)
        );
        foreach ($book->inventories as $inventory) {
            if ($quantity <= 0)
                break;

            $deductedQuantity = min($inventory->quantity, $quantity);

            $inventory->quantity -= $deductedQuantity;
            $this->inventoryRepository->update($inventory);

            $quantity -= $deductedQuantity;
        }
    }
}
