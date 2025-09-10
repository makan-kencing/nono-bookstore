<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Core\View;
use App\Entity\User\Address;
use App\Exception\BadRequestException;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;
use App\Exception\PaymentRequiredException;
use App\Exception\UnprocessableEntityException;
use App\Router\Method\GET;
use App\Router\Method\POST;
use App\Router\Path;
use App\Router\RequireAuth;
use App\Service\CartService;
use App\Service\CheckoutService;
use PDO;
use Stripe\Exception\ApiErrorException;

#[Path('/checkout')]
#[RequireAuth]
readonly class CheckoutController extends WebController
{
    private CartService $cartService;
    private CheckoutService $checkoutService;

    public function __construct(PDO $pdo, View $view)
    {
        parent::__construct($pdo, $view);
        $this->cartService = new CartService($pdo);
        $this->checkoutService = new CheckoutService($pdo);
    }

    #[GET]
    public function viewCheckout(): void
    {
        $cart = $this->cartService->getOrCreateCart();
        assert($cart->user !== null);

        if (count($cart->items) < 1) {
            http_response_code(303);
            $this->redirect('/cart');
            return;
        }

        echo $this->render(
            'webstore/checkout/checkout.php',
            ['cart' => $cart]
        );
    }

    /**
     * @throws ApiErrorException
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws BadRequestException
     */
    #[POST]
    public function createCheckoutSession(): void
    {
        $address = new Address();
        $address->id = (int)($_POST['address_id'] ?? throw new BadRequestException(['message' => 'Missing address_id']));

        try {
            $checkoutSession = $this->checkoutService->createCheckoutSession($address);
        } catch (UnprocessableEntityException) {
            $this->redirect('/checkout');
            return;
        }

        http_response_code(303);
        $this->redirect($checkoutSession->url ?? throw new NotFoundException());
    }

    /**
     * @throws ApiErrorException
     * @throws BadRequestException
     */
    #[GET]
    #[Path('/success')]
    public function onSuccess(): void
    {
        $sessionId = $_GET['id'] ?? throw new BadRequestException();
        assert(is_string($sessionId));

        try {
            $order = $this->checkoutService->checkout($sessionId);
        } catch (PaymentRequiredException $e) {
            $this->redirect($e->redirectTo);
            return;
        }

        echo $this->render(
            'webstore/checkout/success.php',
            ['order' => $order]
        );
    }
}
