<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Book\Book;

assert(isset($book) && $book instanceof Book);
$book->normalizeOrder();

ob_start();
?>
    <main>
        <div style="display: flex; justify-content: end; gap: 1rem;">

            <button id="edit-book" type="button">Edit</button>
            <button id="delete-book" type="button">Delete</button>
        </div>

        <div style="display: flex;">
            <div>
                <?= View::render('admin/book/_sidebar.php', ['book' => $book, 'currentMenu' => 'Book Details']) ?>
            </div>

            <div>
                <h3>Book Details</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="display: flex; flex-flow: column; gap: 1rem;">
                        <div style="display: flex; align-items: start">
                            <div class="carousel">
                                <div class="carousel-images">
                                    <?php foreach ($book->images as $image) : ?>
                                        <div style="order: <?= $image->imageOrder ?>; display: flex;">
                                            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>"
                                                 style="height: 240px;">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <table>
                                <tr>
                                    <th>Work of</th>
                                    <td><?= $book->work->title ?></td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td><?= $book->isbn ?></td>
                                </tr>
                                <tr>
                                    <th>Publisher</th>
                                    <td><?= $book->publisher ?></td>
                                </tr>
                                <tr>
                                    <th>Format</th>
                                    <td><?= $book->coverType->title() ?></td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <h4>Description</h4>

                            <div><?= $book->description ?></div>
                        </div>

                        <table>
                            <tr>
                                <th>Publication Date</th>
                                <td><?= $book->publicationDate ?></td>
                            </tr>
                            <tr>
                                <th>Number of Pages</th>
                                <td><?= $book->numberOfPages ?></td>
                            </tr>
                            <tr>
                                <th>Edition Information</th>
                                <td><?= $book->editionInformation ?></td>
                            </tr>
                            <tr>
                                <th>Language</th>
                                <td><?= $book->language ?></td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td><?= $book->dimensions ?></td>
                            </tr>
                        </table>
                    </div>

                    <div style="display: flex; flex-flow: column; gap: 1rem;">
                        <div>
                            <h4>Authors</h4>

                            <button id="add-author" type="button">Add</button>

                            <table>
                                <thead>
                                <tr>
                                    <th>Author</th>
                                    <th>Role</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($book->authors as $author): ?>
                                    <tr data-id="<?= $author->author->id ?>">
                                        <td><?= $author->author->name ?></td>
                                        <td><?= $author->type?->title() ?></td>
                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <h4>Stocks</h4>

                            <button id="add-stock" type="button">Add</button>

                            <table>
                                <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Stock Count</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($book->inventories as $inventory): ?>
                                    <tr data-id="<?= $inventory->id ?>">
                                        <td><?= $inventory->location->title() ?></td>
                                        <td><?= $inventory->quantity ?></td>
                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <h4>Prices</h4>

                            <button id="add-price" type="button">Add</button>

                            <table>
                                <thead>
                                <tr>
                                    <th>Unit Price</th>
                                    <th>From</th>
                                    <th>Until</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (array_slice($book->prices, 0, 5) as $price): ?>
                                    <tr data-id="<?= $price->id ?>">
                                        <td>RM <?= number_format($price->amount / 100, 2) ?></td>
                                        <td><?= $price->fromDate->format('Y-m-d H:i:s') ?></td>
                                        <td><?= $price->thruDate?->format('Y-m-d H:i:s') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <h4>Costs</h4>

                            <button id="add-cost" type="button">Add</button>

                            <table>
                                <thead>
                                <tr>
                                    <th>Unit Cost</th>
                                    <th>From</th>
                                    <th>Until</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (array_slice($book->costs, 0, 5) as $cost): ?>
                                    <tr data-id="<?= $cost->id ?>">
                                        <td>RM <?= number_format($cost->amount / 100, 2) ?></td>
                                        <td><?= $cost->fromDate->format('Y-m-d H:i:s') ?></td>
                                        <td><?= $cost->thruDate?->format('Y-m-d H:i:s') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?= View::render('admin/book/_edit_book_dialog.php', ['book' => $book]) ?>

    <script>
        $("button#edit-book").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog.book")[0].showModal();
        });

        $("button#delete-book").click(/** @param {jQuery.Event} e */(e) => {
            const confirmation = confirm("Are you sure?");


        });

        $("dialog.book form").submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            $.ajax(
                '/api/book',
                {
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: () => {
                        this.closest("dialog").close();
                    },
                    error: (jqXHR, textStatus, errorThrown) => {

                    }
                }
            );
        });
    </script>

<?php

$title = 'Book';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
