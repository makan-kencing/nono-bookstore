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
use Throwable;

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
    #[PATCH]
    public function updateItem(): void
    {
        $dto = CartItemQuantityDTO::jsonDeserialize($this::getJsonBody());
        $dto->validate();

        $cart = $this->cartService->getOrCreateCart();

        if ($dto->quantity < 0)
            $this->cartService->subtractItem($cart, $dto);
        else if ($dto->quantity > 0)
            $this->cartService->addItem($cart, $dto);
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

    /**
     * @throws BadRequestException
     */
    #[DELETE]
    public function removeItem(): void
    {
        try {
            $bookId = (int) $this::getJsonBody()['book_id'];
        } catch (Throwable) {
            throw new BadRequestException();
        }

        $cart = $this->cartService->getOrCreateCart();
        $this->cartService->removeItem($cart, $bookId);
    }
}
