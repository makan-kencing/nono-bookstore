<?php

declare(strict_types=1);

use App\Core\View;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Book\Book;
use App\Entity\Product\Inventory;
use App\Entity\Product\InventoryLocation;
use function App\Utils\array_find;

assert(isset($book) && $book instanceof Book);
$book->normalizeOrder();

ob_start();
?>
    <main>
        <input type="hidden" name="book_id" value="<?= $book->id ?>">

        <div style="display: flex; justify-content: end; gap: 1rem;">
            <button id="edit-book" type="button">Edit</button>
            <button id="delete-book" type="button">Delete</button>
        </div>

        <div>
            <h3>Book Details</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="display: flex; flex-flow: column; gap: 1rem;">
                    <div style="display: flex; align-items: start">
                        <div class="carousel">
                            <div class="carousel-images">
                                <?php foreach ($book->images as $image) : ?>
                                    <div style="order: <?= $image->imageOrder ?>; display: grid; place-items: stretch">
                                        <div style="grid-area: 1 / 1">
                                            <img src="<?= $image->file->filepath ?>" alt="<?= $image->file->alt ?>"
                                                 style="height: 240px;">
                                        </div>
                                        <form id="remove-image"
                                              action="/api/book/<?= $book->id ?>/image/<?= $image->file->id ?>"
                                              style="grid-area: 1 / 1;">
                                            <button id="upload-image" type="button">
                                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            </button>
                                            <button type="submit">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (count($book->images) === 0): ?>
                                    <div style="display: grid; place-items: stretch">
                                        <div style="grid-area: 1 / 1">
                                            <img
                                                src="https://s.gr-assets.com/assets/nophoto/book/111x148-bcc042a9c91a29c1d680899eff700a03.png"
                                                alt=""
                                                style="height: 240px;">
                                        </div>
                                        <div style="grid-area: 1 / 1">
                                            <button id="upload-image" type="button">
                                                <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
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


                        <form action="/api/book/<?= $book->id ?>/author" id="add-author" style="display: flex;">
                            <label for="author-id">New Author</label>

                            <div>
                                <input type="search" id="author-id" placeholder="Search authors">
                                <select name="author_id" id="author-id" style="display: block; width: 100%;"
                                        required></select>
                            </div>

                            <label for="type"></label>
                            <select name="type" id="type" required>
                                <?php foreach (AuthorDefinitionType::cases() as $type): ?>
                                    <option value="<?= $type->name ?>"><?= $type->title() ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit">Add</button>
                        </form>

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
                                    <td>
                                        <button id="remove-author" type="button">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <h4>Stocks</h4>

                        <table>
                            <thead>
                            <tr>
                                <th>Location</th>
                                <th>Stock Count</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach (InventoryLocation::cases() as $location): ?>
                                <?php $inventory = array_find(
                                    $book->inventories,
                                    fn(Inventory $inventory) => $inventory->location === $location
                                ); ?>

                                <?php if ($inventory !== null): ?>
                                    <tr data-id="<?= $inventory->id ?>">
                                        <form id="edit-inventory"
                                              action="/api/book/<?= $book->id ?>/stock/<?= $inventory->id ?>"
                                              style="display: contents">
                                            <td><?= $inventory->location->title() ?></td>
                                            <td>
                                                <label>
                                                    <input type="hidden" name="location"
                                                           value="<?= $inventory->location->name ?>">
                                                    <input type="number" name="quantity" min="0"
                                                           step="1" value="<?= $inventory->quantity ?>" required>
                                                </label>
                                            </td>
                                            <td>
                                                <button type="submit">Set</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <form id="add-inventory"
                                              action="/admin/book/<?= $book->id ?>/stock"
                                              style="display: contents">
                                            <td><?= $inventory->location->title() ?></td>
                                            <td>
                                                <label>
                                                    <input type="hidden" name="location" value="<?= $location->name ?>">
                                                    <input type="number" name="quantity" min="0" step="1" required>
                                                </label>
                                            </td>
                                            <td>
                                                <button type="submit">Set</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div>
                        <h4>Prices</h4>

                        <form action="/api/book/<?= $book->id ?>/price" id="add-price" style="display: flex;">
                            <label for="price">New Price</label>
                            <input type="number" name="amount" id="price" min="0.01" step="0.01" required>

                            <button type="submit">Add</button>
                        </form>

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

                        <form action="/api/book/<?= $book->id ?>/cost" id="add-cost" style="display: flex;">
                            <label for="cost">New Cost</label>
                            <input type="number" name="amount" id="cost" min="0.01" step="0.01" required>

                            <button type="submit">Add</button>
                        </form>

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
    </main>

<?= View::render('admin/book/_edit_book_dialog.php', ['book' => $book]) ?>
<?= View::render('admin/book/_upload_book_image_dialog.php') ?>

    <script>
        $("button#upload-image").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog.upload-image")[0].showModal();
        });

        $("dialog.upload-image form").submit(/** @param {jQuery.Event} e */(e) => {
            const data = new FormData(e.target);

            $.ajax(
                "/api/book/<?= $book->id ?>/image",
                {
                    method: "POST",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: () => {
                        window.location.reload();
                    },
                    error: (xhr) => {
                    }
                }
            );
        });

        $("button#edit-book").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog.book")[0].showModal();
        });

        $("form#remove-image").submit(/** @param {jQuery.Event} e */function (e) {
            e.preventDefault();

            const confirmation = confirm("Are you sure to remove the image from this book?");
            if (!confirmation)
                return;

            $.ajax(
                e.target.action,
                {
                    method: "DELETE",
                    success: () => {
                        window.location.reload();
                    },
                    error: (xhr) => {
                        console.error(xhr);
                    }
                }
            )
        });

        $("button#delete-book").click(/** @param {jQuery.Event} e */function (e) {
            const confirmation = confirm("Are you sure to delete this book?");
            if (!confirmation)
                return;

            $.ajax(
                `/api/book/${$("input[name='book_id']").val()}`,
                {
                    method: "DELETE",
                    success: () => {
                        window.location.replace("/admin/books");
                    },
                    error: (xhr) => {
                    }
                }
            );
        });

        $("button#remove-author").click(/** @param {jQuery.Event} e */ function (e) {
            const authorId = e.target.closest("tr").dataset.id;

            $.ajax(
                `/api/book/${$("input[name='book_id']").val()}/author/${authorId}`,
                {
                    method: "DELETE",
                    success: () => {
                        window.location.reload();
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                    }
                }
            );
        });

        $("form#edit-inventory").submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            const data = new FormData(e.target);

            $.ajax(
                e.target.action,
                {
                    method: "PUT",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    success: () => {
                        window.location.reload();
                    },
                    error: (xhr) => {
                    }
                }
            );
        });

        $("form#add-author, form#add-price, form#add-cost, form#add-inventory").submit(
            /** @param {jQuery.Event} e */ function (e) {
                e.preventDefault();

                const data = new FormData(e.target);

                $.ajax(
                    e.target.action,
                    {
                        method: "POST",
                        data: JSON.stringify(Object.fromEntries(data.entries())),
                        success: () => {
                            window.location.reload();
                        },
                        error: (xhr) => {
                        }
                    }
                );
            })

        $("dialog.book form").submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            const data = new FormData(e.target);

            $.ajax(
                `/api/book/${data.get("id")}`,
                {
                    method: "PUT",
                    data: JSON.stringify(Object.fromEntries(data.entries())),
                    success: () => {
                        this.closest("dialog").close();
                        window.location.reload();
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                    }
                }
            );
        });

        $("form#add-author input[type=search]").change(/** @param {jQuery.Event} e */ function (e) {
            $.get(
                `/api/author/options/${this.value}`,
                (data) => {
                    $(this).next("select").html(data);
                }
            );
        })
    </script>

<?php

$title = $book->work->title;
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
