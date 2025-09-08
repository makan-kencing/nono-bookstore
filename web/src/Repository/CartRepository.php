<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\User\User;
use PDOException;

readonly class CartRepository extends Repository
{
    public function createGuestCart(): Cart
    {
        $stmt = $this->conn->prepare('
            INSERT INTO cart ()
            VALUES ()
        ');
        $stmt->execute();

        $cart = new Cart();
        $cart->id = (int)$this->conn->lastInsertId() ?: null;
        $cart->user = null;
        $cart->items = [];
        return $cart;
    }

    public function createUserCart(User $user): Cart
    {
        $stmt = $this->conn->prepare('
            INSERT INTO cart (user_id)
            VALUES (:user_id)
        ');
        $stmt->bindValue(':user_id', $user->id);
        $stmt->execute();

        $cart = new Cart();
        $cart->id = (int)$this->conn->lastInsertId() ?: null;
        $cart->user = $user;
        $cart->items = [];
        return $cart;
    }

    public function update(Cart $cart): void
    {
        $stmt = $this->conn->prepare('
            UPDATE cart
            SET user_id = :user_id
            WHERE id = :id
        ');
        $stmt->bindValue(':id', $cart->id);
        $stmt->bindValue(':user_id', $cart->user?->id);
        $stmt->execute();

        foreach ($cart->items as $item)
            try {
                $this->insertItem($item);
            } catch (PDOException) {
                $this->updateItem($item);
            }
    }

    public function insertItem(CartItem $item): void
    {
        $stmt = $this->conn->prepare('
            INSERT INTO cart_item (cart_id, book_id, quantity)
            VALUES (:cart_id, :book_id, :quantity)
        ');
        $stmt->bindValue(':cart_id', $item->cart->id);
        $stmt->bindValue(':book_id', $item->book->id);
        $stmt->bindValue(':quantity', $item->quantity);
        $stmt->execute();
    }

    public function removeItem(CartItem $item): void
    {
        $stmt = $this->conn->prepare('
            DELETE FROM cart_item
            WHERE cart_id = :cart_id
                AND book_id = :book_id
        ');
        $stmt->bindValue(':cart_id', $item->cart->id);
        $stmt->bindValue(':book_id', $item->book->id);
        $stmt->execute();
    }

    public function updateItem(CartItem $item): void
    {
        $stmt = $this->conn->prepare('
            UPDATE cart_item
            SET quantity = :quantity
            WHERE cart_id = :cart_id
                AND book_id = :book_id
        ');
        $stmt->bindValue(':cart_id', $item->cart->id);
        $stmt->bindValue(':book_id', $item->book->id);
        $stmt->bindValue(':quantity', $item->quantity);
    }

    public function clear(int|Cart $cart): void
    {
        if ($cart instanceof Cart) $cart = $cart->id;

        $stmt = $this->conn->prepare('
            DELETE FROM cart_item
            WHERE cart_id = :cart_id
        ');
        $stmt->bindValue(':cart_id', $cart);
        $stmt->execute();
    }
}
