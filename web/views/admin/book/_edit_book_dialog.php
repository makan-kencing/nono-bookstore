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


    $("dialog.book button#add-title").click(/** @param {jQuery.Event} e */ function (e) {
        let search = $(this).prev("input[type=search]")[0];

        $.ajax(
            `/api/work/title/${search.value}`,
            {
                method: "POST",
                success: (data) => {
                    const option = document.createElement("option");
                    option.value = data.id;
                    option.textContent = data.title;

                    $(this).prev("input[type=search]").val(data.title);
                    $(this).parent().next("select")[0].replaceChildren(option);
                },
                error: (xhr) => {
                    console.error(xhr);
                    switch (xhr.status) {
                        case 409:
                            alert("The title already exists.");
                    }
                }
            }
        );
    });

    $("button#add-new-author").click(/** @param {jQuery.Event} e */ function (e) {
        let search = $(this).prev("input[type=search]")[0];

        $.ajax(
            `/api/author/name/${search.value}`,
            {
                method: "POST",
                success: (data) => {
                    const option = document.createElement("option");
                    option.value = data.id;
                    option.textContent = data.name;

                    $(this).prev("input[type=search]").val(data.name);
                    $(this).parent().next("select")[0].replaceChildren(option);
                },
                error: (xhr) => {
                    console.error(xhr);
                    switch (xhr.status) {
                        case 409:
                            alert("The author already exists.");
                    }
                }
            }
        );
    });

    function fetchWorkOptions() {
        $.get(
            `/api/work/options/${this.value}`,
            (data) => {
                $(this).parent().next("select").html(data);
            }
        );
    }

    function fetchAuthorOptions() {
        $.get(
            `/api/author/options/${this.value}`,
            (data) => {
                $(this).parent().next("select").html(data);
            }
        );
    }
</script>
<?php $dialog->endFragment(); ?>

<?php $dialog->start(); ?>
<fieldset>
    <legend>Book Information</legend>

    <input type="hidden" name="id" value="<?= $book->id ?>">

    <div>
        <label for="title">Title*</label>
        <div style="display: flex">
            <input type="search" id="title" placeholder="Search titles"
                   onchange="fetchWorkOptions.call(this)">
            <button id="add-title" type="button"><i class="fa-solid fa-plus"></i></button>
        </div>
        <select name="work[id]" id="title" style="display: block; width: 100%;" required>
            <option value="<?= $book->work->id ?>"><?= $book->work->title ?></option>
        </select>
    </div>

    <div>
        <label for="isbn">ISBN*</label>
        <input type="text" id="isbn" name="isbn" value="<?= $book->isbn ?>" disabled required
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
