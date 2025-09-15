<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Cart\Cart;

assert(isset($cart) && $cart instanceof Cart);

$template = new Template(
    'webstore/_base.php',
    ['title' => 'Shopping Cart']
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/webstore/cart.css">

<?php $template->endFragment(); ?>

<?php $template->start() ?>

<main>
    <div class="cart">
        <section>
            <h2>Shopping Cart</h2>

            <?php if (count($cart->items) == 0): ?>
                <div>
                    <h2>Your cart is empty</h2>

                    <a href="/">Get started</a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Price</th>
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
                                <form>
                                    <div>
                                        <input type="hidden" name="book_id" value="<?= $book->id ?>">

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
                                        <p style="font-weight: bold"><?= $book->coverType->title() ?></p>

                                        <?php if ($totalStocks > 20): ?>
                                            <p>In Stock</p>
                                        <?php elseif ($totalStocks > 0): ?>
                                            <p><?= $totalStocks ?> left</p>
                                        <?php else: ?>
                                            <p>No stock</p>
                                        <?php endif; ?>

                                        <label for="quantity"></label>
                                    </div>

                                    <div style="margin-top: auto">
                                        <button type="submit" class="decrement">
                                            <?php if ($item->quantity > 1): ?>
                                                <i class="fa-solid fa-minus"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-trash"></i>
                                            <?php endif; ?>
                                        </button>

                                        <input type="number" name="quantity" id="quantity" inert
                                               min="0" max="<?= $totalStocks ?>" value="<?= $item->quantity ?>">

                                        <button type="submit" class="increment">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <span style="font-weight: bold">RM <?= number_format($price?->amount / 100, 2) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="100%">
                            Subtotal (<?= $cart->getNumberOfItems() ?> items):
                            <b>RM <?= number_format($cart->getSubtotal() / 100, 2) ?></b>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </section>

        <?php if (count($cart->items) > 0): ?>
            <aside>
                <h3>Checkout</h3>

                <form style="display: contents" action="/checkout">

                    <div>
                        Subtotal (<?= $cart->getNumberOfItems() ?> items):
                        <b>
                            RM <?= number_format($cart->getSubtotal() / 100, 2) ?>
                        </b>
                    </div>

                    <button type="submit"
                        <?php if (!$cart->canCheckout()): ?>
                            disabled
                        <?php endif; ?>
                    >Checkout
                    </button>
                </form>
            </aside>
        <?php endif; ?>
    </div>
</main>

<script>
    $("tr form").submit(/** @param {jQuery.Event} e */(e) => {
        e.preventDefault();

        const quantityInput = e.target.querySelector("input[type=number][name=quantity]");

        let mod = e.originalEvent.submitter.classList.contains("decrement") ? -1 : 1;
        if (quantityInput.valueAsNumber + mod > +quantityInput.max)
            return;

        const data = new FormData(e.target);
        data.set("quantity", mod.toString());

        $.ajax(
            "/api/cart",
            {
                method: "PATCH",
                data: JSON.stringify(Object.fromEntries(data.entries())),
                error: (jqXHR, textStatus, errorThrown) => {
                    console.error(jqXHR, textStatus, errorThrown)
                },
                success: () => {
                    window.location.reload();
                }
            }
        );
    });

</script>
<?= $template->end() ?>
