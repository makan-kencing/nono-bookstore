<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\BookImage;
use App\Entity\Cart\Cart;

assert(isset($cart) && $cart instanceof Cart);

ob_start();
?>
    <div style="display: flex; flex-flow: row">
        <div style="width: 100%">
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
                        usort(
                            $book->images,
                            fn(BookImage $o1, BookImage $o2) => $o1->imageOrder - $o2->imageOrder
                        );
                        usort(
                            $book->authors,
                            function (AuthorDefinition $o1, AuthorDefinition $o2) {
                                if ($o1->type === null) return -1;
                                if ($o2->type === null) return 1;
                                return $o1->type->compareTo($o2->type);
                            }
                        );

                        $totalStocks = $book->getTotalInStock();
                        $price = $book->getCurrentPrice();
                        $author = $book->authors[0];
                        $image = $book->images[0] ?? null;
                        ?>
                        <tr>
                            <td>
                                <?php if ($image !== null): ?>
                                    <img style="height: 200px;" src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
                                <?php else: ?>
                                    <img src="" alt="">
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="/api/cart">
                                    <input type="hidden" name="book_id" value="<?= $book->id ?>">

                                    <h3><a href="/book/<?= $book->isbn ?>/<?= $book->work->slug ?>"><?= $book->work->title ?></a></h3>
                                    <p><?= $author->author->name ?></p>
                                    <p style="font-weight: bold"><?= $book->coverType->title() ?></p>

                                    <?php if ($totalStocks > 20): ?>
                                        <p>In Stock</p>
                                    <?php elseif ($totalStocks > 0): ?>
                                        <p><?= $totalStocks ?> left</p>
                                    <?php else: ?>
                                        <p>No stock</p>
                                    <?php endif; ?>

                                    <label for="quantity"></label>
                                    <div>
                                        <button type="submit" class="decrement"><i class="fa-solid fa-minus"></i></button>
                                        <input type="number" name="quantity" id="quantity" inert
                                               min="0" max="<?= $totalStocks ?>" value="<?= $item->quantity ?>">
                                        <button type="submit" class="increment"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <span style="font-weight: bold"><?= $price?->amount / 100 ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div style="width: max-content">
            <?php if (count($cart->items) > 0): ?>
                <form style="display: contents" action="/checkout">

                    <div style="width: max-content">
                        Subtotal (<?= count($cart->items) ?> items):
                        <span style="font-weight: bold">
                            <?= number_format($cart->getSubtotal() / 100, 2) ?>
                        </span>
                    </div>

                    <button type="submit"
                        <?php if (!$cart->canCheckout()): ?>
                            disabled
                        <?php endif; ?>
                    >Checkout
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

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
                e.target.action,
                {
                    method: "PATCH",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.error(jqXHR, textStatus, errorThrown)
                    },
                    success: () => {
                        // quantityInput.valueAsNumber += mod;
                        // if (quantityInput.valueAsNumber === 0)
                        //     quantityInput.closest("tr").remove();
                        // TODO: make it ajax
                        window.location.reload();
                    }
                }
            );
        });

    </script>
<?php

$title = 'Shopping Cart';
$content = ob_get_clean();

echo View::render(
    'webstore/_base.php',
    ['title' => $title, 'content' => $content]
);
