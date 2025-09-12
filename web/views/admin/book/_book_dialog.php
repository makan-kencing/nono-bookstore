<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Book\Book;
use App\Entity\Product\CoverType;

// params
$id = $id ?? '';
$classes = $classes ?? [];
$title = $title ?? '';
$book = $book ?? null;

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 * @var string $title
 * @var ?Book $book
 */

$dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => $title, 'id' => $id, 'classes' => array_merge(['book'], $classes)]
);

?>

<?php $dialog->startFragment('scripts'); ?>
<script>
    $("dialog.book button#add-author").click(/** @param {jQuery.Event} e */ function (e) {
        const clone = $(this).prev("fieldset").clone();

        let order = parseInt(clone[0].dataset.author);
        order += 1;
        clone[0].dataset.author = order.toString();

        for (const attr of ["for", "name", "id"])
            for (const end of ["[id]", "[type]"])
                clone.find(`[${attr}^=authors][${attr}\$='${end}']`).attr(attr, `authors[${order}]${end}`)

        $(this).before(clone);
    });

    function fetchWorkOptions() {
        $.get(
            `/api/work/options/${this.value}`,
            (data) => {
                $(this).next("select").html(data);
            }
        );
    }

    function fetchAuthorOptions() {
        $.get(
            `/api/author/options/${this.value}`,
            (data) => {
                $(this).next("select").html(data);
            }
        );
    }
</script>

<style>
    dialog.book {
        button#remove-author {
            display: none;
        }

        fieldset + fieldset > button#remove-author {
            display: block;
        }
    }
</style>
<?php $dialog->endFragment(); ?>

<?php $dialog->start(); ?>
<fieldset>
    <legend>Book Information</legend>

    <div>
        <label for="title">Title*</label>
        <input type="search" id="title" placeholder="Search titles" value="<?= $book?->work?->title ?>"
               onchange="fetchWorkOptions.call(this)">
        <select name="work[id]" id="title" style="display: block; width: 100%;" required>
            <?php if ($book !== null): ?>
                <option value="<?= $book->work->id ?>"><?= $book->work->title ?></option>
            <?php endif; ?>
        </select>
    </div>

    <div>
        <label for="isbn">ISBN*</label>
        <input type="text" id="isbn" name="isbn" value="<?= $book?->isbn ?>" required
               pattern="\d{13}" minlength="13" maxlength="13">
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"
                  style="display: block; width: 100%; resize: vertical"><?= $book?->description ?></textarea>
    </div>

    <div>
        <label for="format">Format*</label>
        <select name="cover_type" id="format" required>
            <?php foreach (CoverType::cases() as $format): ?>
                <option value="<?= $format->name ?>" <?= $format === $book?->coverType ? 'selected' : '' ?>>
                    <?= $format->title() ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="number-of-pages">Number of Pages*</label>
        <input type="number" name="number_of_pages" id="number-of-pages" min="1" value="<?= $book?->numberOfPages ?>">
    </div>

    <div>
        <label for="dimensions">Dimensions</label>
        <input type="text" name="dimensions" id="dimensions" value="<?= $book?->dimensions ?>">
    </div>

    <div>
        <label for="language">Language</label>
        <input type="text" name="language" id="language" value="<?= $book?->language ?>">
    </div>

    <div>
        <label for="edition-information">Edition Information</label>
        <input type="text" name="edition_information" id="edition-information"
               value="<?= $book?->editionInformation ?>">
    </div>
</fieldset>

<fieldset>
    <legend>Publisher</legend>

    <div>

        <label for="publisher">Publisher*</label>
        <input type="text" name="publisher" id="publisher" value="<?= $book?->publisher ?>" required>
    </div>

    <div>
        <label for="publication-date">Publication Date*</label>
        <input type="text" name="publication_date" id="publication-date" pattern="[\d\-]+"
               value="<?= $book?->publicationDate ?>" required>
    </div>
</fieldset>

<fieldset>
    <legend>Authors</legend>

    <?php if ($book !== null): ?>
        <?php $i = 0; ?>
        <?php foreach ($book->authors as $author): ?>
            <fieldset data-author="<?= $i ?>">
                <button id="remove-author" type="button"
                        onclick="this.closest('fieldset').remove()">X
                </button>

                <div>
                    <label for="author[<?= $i ?>][id]">Author*</label>
                    <input type="search" id="author[<?= $i ?>][id]" placeholder="Search authors"
                           value="<?= $author->author->name ?>"
                           onchange="fetchAuthorOptions.call(this)">
                    <select name="authors[<?= $i ?>][id]" id="author[<?= $i ?>][id]"
                            style="display: block; width: 100%;" required>
                        <option value="<?= $author->author->id ?>"><?= $author->author->name ?></option>
                    </select>
                </div>

                <div>
                    <label for="author[<?= $i ?>][type]">Role*</label>
                    <select name="authors[<?= $i ?>][type]" id="author[<?= $i ?>][type]" required>
                        <?php foreach (AuthorDefinitionType::cases() as $type): ?>
                            <option
                                value="<?= $type->name ?>" <?= $type === $author->type ? 'selected' : '' ?>><?= $type->title() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>
            <?php $i++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <fieldset data-author="0">
            <button id="remove-author" type="button"
                    onclick="this.closest('fieldset').remove()">X
            </button>

            <div>
                <label for="author[0][id]">Author*</label>
                <input type="search" id="author[0][id]" placeholder="Search authors"
                       onchange="fetchAuthorOptions.call(this)">
                <select name="authors[0][id]" id="author[0][id]" style="display: block; width: 100%;" required></select>
            </div>

            <div>
                <label for="author[0][type]">Role*</label>
                <select name="authors[0][type]" id="author[0][type]" required>
                    <?php foreach (AuthorDefinitionType::cases() as $type): ?>
                        <option value="<?= $type->name ?>"><?= $type->title() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </fieldset>
    <?php endif; ?>

    <button id="add-author" type="button">Add author</button>
</fieldset>
<?= $dialog->end() ?>
