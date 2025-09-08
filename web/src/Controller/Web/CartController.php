<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Router\Method\GET;
use App\Router\Path;
use App\Service\CartService;
use PDO;

#[Path('/cart')]
readonly class CartController extends WebController
{
    private CartService $cartService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->cartService = new CartService($pdo);
    }

    #[GET]
    public function viewCart(): void
    {
        $cart = $this->cartService->getOrCreateCart();

        echo $this->render(
            'webstore/cart.php',
            ['cart' => $cart]
        );
    }
}
