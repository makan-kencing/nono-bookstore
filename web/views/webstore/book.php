<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinition;
use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);
$work = $book->work;

$template = new Template(
    'webstore/_base.php',
    ['title' => $book->work->title ?? 'Book']
);

?>

<?php $template->startFragment('header'); ?>

<link rel="stylesheet" href="/static/styles/webstore/book.css">

<?php $template->endFragment(); ?>

<?php $template->start() ?>
    <main>
        <section id="book-listing">
            <div id="book-preview" class="carousel">
                <div class="carousel-images">
                    <?php foreach ($book->images as $image) : ?>
                        <div style="order: <?= $image->imageOrder ?>; display: flex;">
                            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="book-information">
                <h2><?= $work->title ?></h2>

                <div>by
                    <ul class="comma-list">
                        <?php usort(
                            $book->authors,
                            function (AuthorDefinition $o1, AuthorDefinition $o2) {
                                if ($o1->type === null) return -1;
                                if ($o2->type === null) return 1;
                                return $o1->type->compareTo($o2->type);
                            }
                        )?>
                        <?php foreach ($book->authors as $author) : ?>
                            <li>
                                <a href="/author/<?= $author->author->slug ?>"><?= $author->author->name ?></a>
                                <?php if ($author->type !== null): ?>
                                    (<?= $author->type->title() ?>)
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($work->series) : ?>
                    <p>
                        <a href="/series/<?= $work->series->series->slug ?>">
                            Part of: <?= $work->series->series->name ?></a>
                    </p>
                <?php endif; ?>
            </div>

            <div id="book-purchase">
                <form action="/api/cart" id="add-to-cart">
                    <input type="hidden" name="book_id" value="<?= $book->id ?>">

                    <div id="product-variants">
                        <?php foreach ($work->books as $otherBook): ?>
                            <?php if ($otherBook->getCurrentPrice() === null) continue; ?>
                            <?php if ($otherBook->id === $book->id): ?>
                                <div class="product-variant">
                                    <p class="type"><?= $otherBook->coverType->title() ?></p>
                                    <p><?= $otherBook->language ?></p>
                                    <p class="price">RM <?= number_format($otherBook->getCurrentPrice()->amount / 100, 2) ?></p>
                                </div>
                            <?php else: ?>
                                <a href="/book/<?= $otherBook->isbn ?>/<?= $work->slug ?>"
                                   class="product-variant">
                                    <p class="type"><?= $otherBook->coverType->title() ?></p>
                                    <p><?= $otherBook->language ?></p>
                                    <p class="price">RM <?= number_format($otherBook->getCurrentPrice()->amount / 100, 2) ?></p>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <div id="product-ordering">
                        <?php $inventory = $book->getClosestStock() ?>
                        <?php $total = $book->getTotalInStock() ?>
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

                        $.ajax(
                            e.target.action,
                            {
                                method: "PATCH",
                                data: JSON.stringify(Object.fromEntries(data.entries())),
                                error: (jqXHR, textStatus, errorThrown) => {
                                    console.error(jqXHR, textStatus, errorThrown)
                                },
                                success: () => {
                                }
                            }
                        );
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
                    <td><?= $book->publisher ?></td>
                </tr>
                <tr>
                    <td>Publication Date</td>
                    <td><?= $book->publicationDate ?></td>
                </tr>
                <?php if ($work->series !== null) : ?>
                    <tr>
                        <td>Series</td>
                        <td><?= $work->series->series->name ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Pages</td>
                    <td><?= $book->numberOfPages ?></td>
                </tr>
                <?php if ($book->dimensions !== null): ?>
                    <tr>
                        <td>Product dimensions</td>
                        <td><?= $book->dimensions ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($book->editionInformation !== null): ?>
                <tr>
                    <td>Edition Information</td>
                    <td><?= $book->editionInformation ?></td>
                </tr>
                <?php endif; ?>
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
<?= $template->end() ?>
