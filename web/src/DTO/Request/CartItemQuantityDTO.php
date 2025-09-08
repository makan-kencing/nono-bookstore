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
                $json['book_id'],
                $json['quantity']
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

        $rules = [];
        if ($this->quantity <= 0)
            $rules[] = [
                "field" => "quantity",
                "type" => "amount",
                "reason" => "Must be more than 0"
            ];

        if ($rules)
            throw new UnprocessableEntityException($rules);
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
