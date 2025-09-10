<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use function App\Utils\array_group_by;

assert(isset($cart) && $cart instanceof Cart);
assert($cart->user !== null);

ob_start();
?>
    <div style="display: flex; flex-flow: row">
        <form action="/checkout" method="post" style="display: contents">
            <div style="width: 100%">
                <h2>Checkout</h2>

                <fieldset>
                    <legend>Select a delivery address</legend>

                    <legend>Delivery addresses (<?= count($cart->user->addresses) ?>)</legend>

                    <label style="display: none"><input type="radio" name="address_id" required></label>
                    <?php foreach ($cart->user->addresses as $address): ?>
                        <label style="display: flex; align-items: center">
                            <input type="radio" name="address_id" value="<?= $address->id ?>" <?= $address->id === $cart->address?->id ? 'checked' : '' ?>>

                            <span>
                                <span style="display: flex; flex-flow: column;">
                                    <span style="font-weight: bold"><?= $address->name ?></span>
                                    <span>
                                        <?= implode(', ', [$address->address1, $address->address2, $address->address3]) ?>
                                        <?= implode(', ', [$address->state, $address->postcode, $address->country]) ?>
                                    </span>
                                    <span>Phone number: <?= $address->phoneNumber ?></span>
                                </span>

                                <button type="button">Edit address</button>
                            </span>
                        </label>
                    <?php endforeach; ?>

                    <button type="button">Add a new delivery address</button>
                </fieldset>

                <?php $shippingGroupedItems = array_group_by(
                    $cart->items,
                    fn(CartItem $item) => $item->book->getClosestStock()?->location->getEstimatedShipping()
                ); ?>

                <?php foreach ($shippingGroupedItems as $estimatedShipping => $items): ?>
                    <div>
                        <h3><?= $estimatedShipping ?></h3>

                        <div>
                            <?php foreach ($items as $item): ?>
                                <?= View::render('webstore/_component/_checkout_item.php', ['item' => $item]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="width: max-content">
                <?php if (count($cart->items) > 0): ?>
                    <h2>Summary</h2>

                    <table style="width: max-content">
                        <tbody>
                        <tr>
                            <td>Subtotal</td>
                            <td><?= number_format($cart->getSubtotal() / 100, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Shipping & Handling</td>
                            <td><?= number_format($cart->getShipping() / 100, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td><?= number_format($cart->getTotal() / 100, 2) ?></td>
                        </tr>
                        </tbody>
                    </table>

                    <button type="submit" <?= !$cart->canCheckout() ? 'disabled' : '' ?>>
                        Place your order
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

<?php

$title = 'Checkout';
$content = ob_get_clean();

echo View::render(
    'webstore/_base.php',
    ['title' => $title, 'content' => $content]
);
