<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\View;
use App\Entity\Book\BookImage;
use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Order\Invoice;
use App\Entity\Order\Order;
use App\Entity\Order\Payment;
use App\Entity\Order\Shipment;
use App\Entity\User\Address;
use App\Exception\ForbiddenException;
use App\Exception\PaymentRequiredException;
use App\Exception\UnprocessableEntityException;
use App\Mailer\MailService;
use App\Repository\OrderRepository;
use DateTime;
use PDO;
use RuntimeException;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\StripeClient;
use Throwable;
use function App\Utils\array_find;

readonly class CheckoutService extends Service
{
    private StripeClient $stripe;
    private OrderRepository $orderRepository;
    private CartService $cartService;
    private InventoryService $inventoryService;

    private MailService $mailService;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->orderRepository = new OrderRepository($pdo);
        $this->cartService = new CartService($pdo);
        $this->inventoryService = new InventoryService($pdo);
        $this->stripe = new StripeClient(getenv('STRIPE_SK_KEY')
            ?: throw new RuntimeException('No STRIPE_SK_KEY'));
        $this->mailService = new MailService();
    }

    /**
     * @throws ForbiddenException
     * @throws ApiErrorException
     * @throws UnprocessableEntityException
     */
    public function createCheckoutSession(Address $address): Session
    {
        $cart = $this->cartService->getOrCreateCart();
        assert($cart->user !== null);

        if (!$cart->canCheckout())
            throw new UnprocessableEntityException(['message' => 'Cart could not be checked out due to insufficient stocks']);

        $selectedAddress = array_find($cart->user->addresses, fn(Address $userAddress) => $userAddress->id === $address->id);
        if ($selectedAddress === null)
            throw new ForbiddenException();

        $this->cartService->updateCartAddress($cart, $selectedAddress);

        return $this->stripe->checkout->sessions->create([
            'customer_email' => $cart->user->email,
            'line_items' =>
                array_map(
                /**
                 * @throws UnprocessableEntityException
                 */
                    fn(CartItem $item) => [
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => [
                                'name' => $item->book->work->title,
                                'images' => array_map(fn(BookImage $image) => $image->file->filepath, array_values($item->book->images))
                            ],
                            'unit_amount' => $item->book->getCurrentPrice()->amount ?? throw new UnprocessableEntityException(
                                    ['message' => $item->book->isbn . ' does not have a price']
                                )
                        ],
                        'quantity' => $item->quantity
                    ], array_values($cart->items)
                ),
            'shipping_options' => [
                [
                    'shipping_rate_data' => [
                        'type' => 'fixed_amount',
                        'fixed_amount' => [
                            'amount' => $cart->getShipping(),
                            'currency' => 'myr'
                        ],
                        'display_name' => $cart->getShipping() == 0 ? 'Free Shipping' : 'Standard Shipping'
                    ]
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->getSiteUrl() . '/checkout/success?id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->getSiteUrl() . '/checkout',
        ]);
    }

    /**
     * @throws ApiErrorException
     * @throws PaymentRequiredException
     */
    public function checkout(string $sessionId): Order
    {
        $checkoutSession = $this->stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => [
                'payment_intent',
                'payment_intent.payment_method'
            ]
        ]);

        if ($checkoutSession->payment_status === 'unpaid')
            throw new PaymentRequiredException($checkoutSession->url ?? '/checkout');

        $cart = $this->cartService->getOrCreateCart();

        try {
            $this->pdo->beginTransaction();

            $order = $this->createOrder($cart, $checkoutSession);

            foreach ($order->items as $item)
                $this->inventoryService->deductStocks($item->book, $item->quantity);

            $this->orderRepository->createCheckout($order);

            $this->cartService->clearCart($cart);

            $this->pdo->commit();

            $this->sendInvoiceEmail($order);
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $order;
    }

    public function createOrder(Cart $cart, Session $session): Order
    {
        assert($cart->user !== null);
        assert($session->payment_intent instanceof PaymentIntent);
        assert($session->payment_intent->payment_method instanceof PaymentMethod);

        $order = new Order();
        $order->user = $cart->user;
        $order->address = $cart->address;
        $order->refNo = $session->id;
        $order->orderedAt = new DateTime();
        $order->items = array_map(
            fn(CartItem $item) => $item->toOrderItem($order),
            $cart->items
        );
        $order->adjustments = $cart->toOrderAdjustments($order);

        $invoice = new Invoice();
        $invoice->order = $order;
        $invoice->invoicedAt = new DateTime();
        $order->invoice = $invoice;

        $payment = new Payment();
        $payment->invoice = $invoice;
        $payment->refNo = $session->payment_intent->id;
        $payment->method = $session->payment_intent->payment_method->type;
        $payment->amount = $session->payment_intent->amount;
        $payment->paidAt = (new DateTime())->setTimestamp($session->payment_intent->created);
        $invoice->payment = $payment;

        $shipment = new Shipment();
        $shipment->order = $order;
        $shipment->readyAt = null;
        $shipment->shippedAt = null;
        $shipment->arrivedAt = null;
        $shipment->updatedAt = new DateTime();
        $order->shipment = $shipment;

        return $order;
    }

    private function sendInvoiceEmail(Order $order): void
    {
        $html = View::render('email/invoice.php', ['order' => $order]);

        $this->mailService->sendMail(
            $order->user->email,
            "Invoice for Order #" . $order->refNo,
            $html
        );
    }

}
