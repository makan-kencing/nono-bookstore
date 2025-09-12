<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Book\Book;
use App\Entity\Product\CoverType;

// params
$id ??= '';
$classes ??= [];

assert(isset($book) && $book instanceof Book);

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 */

$dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Edit Book', 'id' => $id, 'classes' => array_merge(['book'], $classes)]
);

?>

<?php $dialog->startFragment('scripts'); ?>
<script>
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
<?php $dialog->endFragment(); ?>

<?php $dialog->start(); ?>
<fieldset>
    <legend>Book Information</legend>

    <div>
        <label for="title">Title*</label>
        <input type="search" id="title" placeholder="Search titles" value="<?= $book->work->title ?>"
               onchange="fetchWorkOptions.call(this)">
        <select name="work[id]" id="title" style="display: block; width: 100%;" required>
            <option value="<?= $book->work->id ?>"><?= $book->work->title ?></option>
        </select>
    </div>

    <div>
        <label for="isbn">ISBN*</label>
        <input type="text" id="isbn" name="isbn" value="<?= $book->isbn ?>" required
               pattern="\d{13}" minlength="13" maxlength="13">
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"
                  style="display: block; width: 100%; resize: vertical"><?= $book->description ?></textarea>
    </div>

    <div>
        <label for="format">Format*</label>
        <select name="cover_type" id="format" required>
            <?php foreach (CoverType::cases() as $format): ?>
                <option value="<?= $format->name ?>" <?= $format === $book->coverType ? 'selected' : '' ?>>
                    <?= $format->title() ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="number-of-pages">Number of Pages*</label>
        <input type="number" name="number_of_pages" id="number-of-pages" min="1" value="<?= $book->numberOfPages ?>">
    </div>

    <div>
        <label for="dimensions">Dimensions</label>
        <input type="text" name="dimensions" id="dimensions" value="<?= $book->dimensions ?>">
    </div>

    <div>
        <label for="language">Language</label>
        <input type="text" name="language" id="language" value="<?= $book->language ?>">
    </div>

    <div>
        <label for="edition-information">Edition Information</label>
        <input type="text" name="edition_information" id="edition-information"
               value="<?= $book->editionInformation ?>">
    </div>
</fieldset>

<fieldset>
    <legend>Publisher</legend>

    <div>

        <label for="publisher">Publisher*</label>
        <input type="text" name="publisher" id="publisher" value="<?= $book->publisher ?>" required>
    </div>

    <div>
        <label for="publication-date">Publication Date*</label>
        <input type="text" name="publication_date" id="publication-date" pattern="[\d\-]+"
               value="<?= $book->publicationDate ?>" required>
    </div>
</fieldset>
<?= $dialog->end() ?>
