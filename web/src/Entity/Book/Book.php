<?php

declare(strict_types=1);

namespace App\Entity\Book;

use function App\Utils\array_find;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Cart\CartItem;
use App\Entity\Order\OrderItem;
use App\Entity\Product\Cost;
use App\Entity\Product\CoverType;
use App\Entity\Product\Inventory;
use App\Entity\Product\Price;
use App\Orm\Attribute\Id;
use App\Orm\Attribute\ManyToOne;
use App\Orm\Attribute\OneToMany;
use App\Orm\Entity;
use DateTime;

class Book extends Entity
{
    #[Id]
    public ?int $id;

    #[ManyToOne]
    public Work $work;

    public string $isbn;

    public ?string $description;

    public string $publisher;

    public string $publicationDate;

    public int $numberOfPages;

    public CoverType $coverType;

    public ?string $editionInformation;

    public ?string $language;

    public ?string $dimensions;

    public ?DateTime $deletedAt;

    /** @var BookImage[] */
    #[OneToMany(BookImage::class, mappedBy: 'book')]
    public array $images;

    /** @var AuthorDefinition[] */
    #[OneToMany(AuthorDefinition::class, mappedBy: 'book')]
    public array $authors;

    /** @var Cost[] */
    #[OneToMany(Cost::class, mappedBy: 'book')]
    public array $costs;

    /** @var Price[] */
    #[OneToMany(Price::class, mappedBy: 'book')]
    public array $prices;

    /** @var Inventory[] */
    #[OneToMany(Inventory::class, mappedBy: 'book')]
    public array $inventories;

    /** @var CartItem[] */
    #[OneToMany(CartItem::class, mappedBy: 'book')]
    public array $inCarts;

    /** @var OrderItem[] */
    #[OneToMany(OrderItem::class, mappedBy: 'book')]
    public array $inOrders;

    public function getCurrentPrice(): ?Price
    {
        $now = new DateTime();
        assert($now != null);

        return array_find(
            $this->prices,
            fn(Price $price) => $now->getTimestamp() > $price->fromDate->getTimestamp()
                && ($price->thruDate == null || $now->getTimestamp() < $price->thruDate->getTimestamp())
        );
    }

    public function getTotalInStock(): int
    {
        return array_reduce(
            $this->inventories,
            fn (int $carry, Inventory $inventory) => $carry + $inventory->quantity,
            0
        );
    }

    public function getClosestStock(): ?Inventory
    {
        uasort(
            $this->inventories,
            fn (Inventory $a, Inventory $b) => $a->location->compareTo($b->location)
        );
        return current($this->inventories) ?: null;
    }

    public function normalizeOrder(): void
    {
        if (isset($this->images))
            usort(
                $this->images,
                fn(BookImage $o1, BookImage $o2) => $o1->imageOrder - $o2->imageOrder
            );
        if (isset($this->authors))
            usort(
                $this->authors,
                function (AuthorDefinition $o1, AuthorDefinition $o2) {
                    if ($o1->type === null) return -1;
                    if ($o2->type === null) return 1;
                    return $o1->type->compareTo($o2->type);
                }
            );
        if (isset($this->inventories))
            usort(
                $this->inventories,
                fn (Inventory $o1, Inventory $o2) => $o1->location->compareTo($o2->location)
            );
        if (isset($this->prices))
            usort(
                $this->prices,
                fn (Price $o1, Price $o2) => -$o1->fromDate->getTimestamp() + $o2->fromDate->getTimestamp()
            );
        if (isset($this->costs))
            usort(
                $this->costs,
                fn (Cost $o1, Cost $o2) => -$o1->fromDate->getTimestamp() + $o2->fromDate->getTimestamp()
            );

    }
}
