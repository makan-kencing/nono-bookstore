<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Core\View;
use App\DTO\Request\CartItemQuantityDTO;
use App\Exception\BadRequestException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\DELETE;
use App\Router\Method\PATCH;
use App\Router\Method\POST;
use App\Router\Method\PUT;
use App\Router\Path;
use App\Service\CartService;
use PDO;

#[Path('/api/cart')]
readonly class CartController extends ApiController
{
    private CartService $cartService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->cartService = new CartService($pdo);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[POST]
    #[Path('/add')]
    public function addItem(): void
    {
        $dto = CartItemQuantityDTO::jsonDeserialize($this::getJsonBody());
        $dto->validate();

        $cart = $this->cartService->getOrCreateCart();
        $this->cartService->addItem($cart, $dto);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[POST]
    #[Path('/remove')]
    public function subtractItem(): void
    {
        $dto = CartItemQuantityDTO::jsonDeserialize($this::getJsonBody());
        $dto->validate();

        $cart = $this->cartService->getOrCreateCart();
        $this->cartService->subtractItem($cart, $dto);
    }

    /**
     * @throws BadRequestException
     * @throws UnprocessableEntityException
     */
    #[PUT]
    public function setItem(): void
    {
        $dto = CartItemQuantityDTO::jsonDeserialize($this::getJsonBody());
        $dto->validate();

        $cart = $this->cartService->getOrCreateCart();
        $this->cartService->setItem($cart, $dto);
    }

    #[DELETE]
    #[Path('/{bookId}')]
    public function removeItem(string $bookId): void
    {
        $cart = $this->cartService->getOrCreateCart();
        $this->cartService->removeItem($cart, (int) $bookId);
    }
}
