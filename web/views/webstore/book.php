<?php

declare(strict_types=1);

use App\Entity\Book\Book;
use App\Entity\Product\Product;

assert(isset($book) && $book instanceof Book);
assert(isset($selectedProduct) && $selectedProduct instanceof Product);

$title = $book->title ?? 'Book';

ob_start();
?>

    <main>
        <section id="book-listing">
            <div id="book-preview" class="carousel">
                <div class="carousel-images">
                    <?php foreach ($book->images as $image) : ?>
                        <div style="order: <?= $image->imageOrder ?>">
                            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="book-information">
                <h2><?= $book->title ?></h2>

                <div>by
                    <ul class="comma-list">
                        <?php foreach ($book->authors as $author) : ?>
                            <li>
                                <a href="/author/<?= $author->author->slug ?>"><?= $author->author->name ?></a>
                                (<?= $author->type->value ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($book->series) : ?>
                    <p>
                        <a href="/series/<?= $book->series->series->slug ?>">
                            Part of: <?= $book->series->series->name ?></a>
                    </p>
                <?php endif; ?>
            </div>

            <div id="book-purchase">
                <form action="/api/cart" id="add-to-cart">
                    <input type="hidden" name="isbn" value="<?= $book->isbn ?>">
                    <input type="hidden" name="type" value="<?= $selectedProduct->coverType->value ?>">

                    <div id="product-variants">
                        <?php foreach ($book->products as $product): ?>
                            <?php if ($product->id == $selectedProduct->id): ?>
                                <div class="product-variant">
                                    <p class="type"><?= $product->coverType->title() ?></p>
                                    <p class="price"><?= $product->getCurrentPrice()?->amount / 100 ?></p>
                                </div>
                            <?php else: ?>
                                <a href="/book/<?= $book->isbn ?>/<?= $book->slug ?>/<?= $product->coverType->value ?>"
                                   class="product-variant">
                                    <p class="type"><?= $product->coverType->title() ?></p>
                                    <p class="price"><?= $product->getCurrentPrice()?->amount / 100 ?></p>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <div id="product-ordering">
                        <?php $inventory = $selectedProduct->getClosestStock() ?>
                        <?php $total = $selectedProduct->getTotalInStock() ?>
                        <div id="product-stocks">
                            <?php if ($inventory != null): ?>
                                <p><i class="fa-solid fa-check"></i>
                                    In stock.
                                    <?php if ($total < 50): ?>
                                        (<?= $total ?> left)
                                    <?php endif; ?>
                                </p>
                                <p><?= $inventory->location->getEstimatedShipping() ?></p>
                            <?php else: ?>
                                <p><i class="fa-solid fa-x"></i> No products in stock</p>
                            <?php endif; ?>
                        </div>

                        <?php if ($total > 0): ?>
                        <div id="product-quantity">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity"
                                   value="1" min="1" max="<?= $total ?>" step="1">
                            <button type="submit">Add to Cart</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>

                <script>
                    $("#add-to-cart").submit(/** @param {jQuery.Event} e */(e) => {
                        e.preventDefault();

                        const data = new FormData(e.target);
                        console.log(data);
                        // TODO: do stuff
                        $.ajax(
                            e.target.
                        );

                        e.target.dataset.wishlist = '1';
                    });
                </script>

                <form action="/api/wishlist" method="post" id="wishlisting" data-wishlist="0">
                    <input type="hidden" name="isbn" value="<?= $book->isbn ?>">

                    <button id="wishlist" type="submit" class="double-icon">
                        <i class="fa-solid fa-heart hide"></i>
                        <i class="fa-regular fa-heart show"></i>

                        <span>Add to wishlist</span>
                    </button>

                    <div id="wishlisted">
                        <i class="fa-solid fa-heart"></i>

                        <span>Wishlisted</span>
                    </div>
                </form>

                <script>
                    $("#wishlisting").submit(/** @param {jQuery.Event} e */(e) => {
                        e.preventDefault();

                        const data = new FormData(e.target);
                        console.log(data);
                        // TODO: do stuff

                        e.target.dataset.wishlist = '1';
                    });
                </script>
            </div>
        </section>

        <section id="synopsis">
            <h3>Synopsis</h3>

            <div>
                <p><?= $book->description ?></p>
            </div>
        </section>

        <section id="book-details">
            <h3>Details</h3>

            <table>
                <tr>
                    <td>ISBN-13</td>
                    <td><?= $book->isbn ?></td>
                </tr>
                <tr>
                    <td>Publisher</td>
                    <td><?= $book->publisher->name ?></td>
                </tr>
                <tr>
                    <td>Publication Date</td>
                    <td><?= $book->publishedDate ?></td>
                </tr>
                <?php if ($book->series) : ?>
                    <tr>
                        <td>Series</td>
                        <td><?= $book->series->series->name ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Pages</td>
                    <td><?= $book->numberOfPages ?></td>
                </tr>
                <tr>
                    <td>Product dimensions</td>
                    <td><?= $book->dimensions ?></td>
                </tr>
                <tr>
                    <td>Language</td>
                    <td><?= $book->language ?></td>
                </tr>
            </table>
        </section>

        <section id="review">
            <h3>Reviews</h3>

            <div></div>
        </section>
    </main>

<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
