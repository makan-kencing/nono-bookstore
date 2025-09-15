<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use function App\Utils\array_group_by;

assert(isset($cart) && $cart instanceof Cart);
assert($cart->user !== null);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Checkout']
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/webstore/cart.css">
<link rel="stylesheet" href="/static/styles/webstore/checkout.css">

<?php $template->endFragment(); ?>

<?php $template->start(); ?>

<main>
    <div class="cart">
        <form id="checkout" action="/checkout" method="post">
        <section>
                <h2>Checkout</h2>

                <fieldset>
                    <legend>Select a delivery address</legend>

                    <legend>Delivery addresses (<?= count($cart->user->addresses) ?>)</legend>

                    <label style="display: none"><input type="radio" name="address_id" required></label>
                    <?php foreach ($cart->user->addresses as $address): ?>
                        <label style="display: flex; align-items: center">
                            <input type="radio" name="address_id"
                                   value="<?= $address->id ?>"
                                   <?php if ($cart->address !== null): ?>
                                        <?= $address->id === $cart->address->id ? 'checked' : '' ?>
                                   <?php else: ?>
                                        <?= $address->id === $cart->user->defaultAddress ? 'checked' : '' ?>
                                   <?php endif; ?>>

                            <span>
                                <span style="display: flex; flex-flow: column;">
                                    <span style="font-weight: bold"><?= $address->name ?></span>
                                    <span>
                                        <?= implode(', ', [$address->address1, $address->address2, $address->address3]) ?>
                                        <?= implode(', ', [$address->state, $address->postcode, $address->country]) ?>
                                    </span>
                                    <span>Phone number: <?= $address->phoneNumber ?></span>
                                </span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <?php $shippingGroupedItems = array_group_by(
                    $cart->items,
                    fn(CartItem $item) => $item->book->getClosestStock()?->location->getEstimatedShipping()
                ); ?>

                <?php foreach ($shippingGroupedItems as $estimatedShipping => $items): ?>
                    <fieldset>
                        <legend><?= $estimatedShipping ?></legend>

                        <table>
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($cart->items as $item): ?>
                                <?php
                                $book = $item->book;
                                $book->normalizeOrder();

                                $totalStocks = $book->getTotalInStock();
                                $price = $book->getCurrentPrice();
                                $image = $book->images[0] ?? null;
                                ?>

                                <tr>
                                    <td>
                                        <?php if ($image !== null): ?>
                                            <img src="<?= $image->file->filepath ?>"
                                                 alt="<?= $image->file->alt ?>">
                                        <?php else: ?>
                                            <img src="" alt="">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <h3>
                                            <a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a>
                                        </h3>

                                        <p>by
                                            <?=
                                            implode(', ', array_map(
                                                fn(AuthorDefinition $author) => "<span>{$author->author->name}</span>",
                                                $book->authors
                                            ))
                                            ?>
                                        </p>

                                        <?php if ($totalStocks > 20): ?>
                                            <p>In Stock</p>
                                        <?php elseif ($totalStocks > 0): ?>
                                            <p><?= $totalStocks ?> left</p>
                                        <?php else: ?>
                                            <p>No stock</p>
                                        <?php endif; ?>

                                        <b>RM <?= number_format($price?->amount / 100, 2) ?></b>
                                    </td>
                                    <td>
                                        <?= $item->quantity ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </fieldset>
                <?php endforeach; ?>
        </section>

        <?php if (count($cart->items) > 0): ?>
            <aside>
                <h2>Summary</h2>

                <table style="width: max-content">
                    <tbody>
                    <tr>
                        <td>Items (<?= $cart->getNumberOfItems() ?>)</td>
                        <td>RM <?= number_format($cart->getSubtotal() / 100, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Shipping & Handling</td>
                        <td>RM <?= number_format($cart->getShipping() / 100, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>RM <?= number_format($cart->getTotal() / 100, 2) ?></td>
                    </tr>
                    </tbody>
                </table>

                <button form="checkout" type="submit" <?= !$cart->canCheckout() ? 'disabled' : '' ?>>
                    Place your order
                </button>
            </aside>
        <?php endif; ?>
        </form>
    </div>
</main>
<?= $template->end() ?>
