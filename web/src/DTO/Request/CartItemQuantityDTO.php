<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Entity\Book\Book;
use App\Entity\Cart\CartItem;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use Throwable;

readonly class CartItemQuantityDTO extends RequestDTO
{
    public function __construct(
        public int $bookId,
        public int $quantity
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function jsonDeserialize(mixed $json): self
    {
        if (!is_array($json))
            throw new BadRequestException();

        try {
            return new self(
                (int) $json['book_id'],
                (int) $json['quantity']
            );
        } catch (Throwable) {
            throw new BadRequestException();
        }
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
    }

    public function toCartItemReference(): CartItem
    {
        $item = new CartItem();
        $item->book = new Book();
        $item->book->id = $this->bookId;
        $item->quantity = $this->quantity;

        return $item;
    }
}
