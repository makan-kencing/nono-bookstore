<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book\Book;
use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Exception\UnauthorizedException;
use App\Repository\CartRepository;
use App\Repository\Query\CartCriteria;
use App\Repository\Query\CartQuery;
use PDO;

readonly class CartService extends Service
{
    public const string CART = 'cart';

    private CartRepository $cartRepository;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->cartRepository = new CartRepository($pdo);

        if (session_status() !== PHP_SESSION_ACTIVE)
            session_start();
    }

    public function getOrCreateCart(): Cart
    {
        $guestCart = $this->getGuestCart();
        $userCart = $this->getUserCart();

        if ($guestCart !== null && $userCart !== null) {
            $userCart = $this->mergeCart($guestCart, $userCart);
            $this->invalidateGuestCart();
        }

        if ($userCart !== null)
            return $userCart;

        try {
            return $this->getOrCreateUserCart();
        } catch (UnauthorizedException) {
            return $this->getOrCreateGuestCart();
        }
    }

    private function getOrCreateGuestCart(): Cart
    {
        $cart = $this->getGuestCart();
        if ($cart !== null)
            return $cart;

        return $this->cartRepository->createGuestCart();
    }

    private function getGuestCart(): ?Cart
    {
        $id = $_SESSION[self::CART] ?? null;
        if ($id === null)
            return null;

        $qb = CartQuery::forShoppingCart()
            ->where(CartCriteria::byId(alias: 'c'))
            ->bind(':id', $id);

        $cart = $this->cartRepository->getOne($qb);
        if ($cart === null)
            $this->invalidateGuestCart();

        return $cart;
    }

    private function invalidateGuestCart(): void
    {
        unset($_SESSION[self::CART]);
    }

    /**
     * @throws UnauthorizedException
     */
    private function getOrCreateUserCart(): Cart
    {
        $context = $this->getSessionContext();
        if ($context === null)
            throw new UnauthorizedException();

        $cart = $this->getUserCart();
        if ($cart !== null)
            return $cart;

        return $this->cartRepository->createUserCart($context->toUserReference());
    }

    private function getUserCart(): ?Cart
    {
        $context = $this->getSessionContext();
        if ($context === null)
            return null;

        $qb = CartQuery::forShoppingCart()
            ->where(CartCriteria::byUserId(alias: 'c'))
            ->bind(':user_id', $context->id);

        return $this->cartRepository->getOne($qb);
    }

    public function mergeCart(Cart $source, Cart $target): Cart
    {
        foreach ($source->items as $item)
            $this->addItem($target, $item->book, $item->quantity);

        return $target;
    }

    public function addItem(Cart $cart, Book $book, int $quantity): void
    {
        foreach ($cart->items as $item)
            if ($item->book->id === $book->id) {
                $item->quantity += $quantity;
                $this->cartRepository->updateItem($item);
                return;
            }

        $item = new CartItem();
        $item->cart = $cart;
        $item->quantity = $quantity;

        $cart->items[] = $item;
        $this->cartRepository->insertItem($item);
    }

    public function setItem(Cart $cart, Book $book, int $quantity): void
    {
        foreach ($cart->items as $item)
            if ($item->book->id === $book->id) {
                $item->quantity = $quantity;
                $this->cartRepository->updateItem($item);
                return;
            }

        $item = new CartItem();
        $item->cart = $cart;
        $item->quantity = $quantity;

        $cart->items[] = $item;
        $this->cartRepository->insertItem($item);
    }

    public function subtractItem(Cart $cart, Book $book, int $quantity): void
    {
        $cart->items = array_filter(
            $cart->items,
            function (CartItem $item) use ($book, $quantity) {
                if ($item->book->id !== $book->id)
                    return true;

                $item->quantity -= $quantity;
                if ($item->quantity > 0) {
                    $this->cartRepository->updateItem($item);
                    return true;
                }

                $this->cartRepository->removeItem($item);
                return false;
            }
        );
    }

    public function removeItem(Cart $cart, Book $book): void
    {
        $cart->items = array_filter(
            $cart->items,
            function (CartItem $item) use ($book) {
                if ($item->book->id !== $book->id)
                    return true;

                $this->cartRepository->removeItem($item);
                return false;
            }
        );
    }

}
