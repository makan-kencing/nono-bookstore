<?php

declare(strict_types=1);

use App\Core\Template;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Product\CoverType;
use App\Entity\Product\InventoryLocation;

// params
$id = $id ?? '';
$classes = $classes ?? [];

/**
 * @var literal-string $id
 * @var literal-string[] $classes
 */

$dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Add Book', 'id' => $id, 'classes' => array_merge(['book'], $classes)]
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

    $("dialog.book input[name=isbn").change(/** @param {jQuery.Event} e */ function (e) {
        const $this = $(this);

        $.ajax(
            `/api/book/isbn/${$this.val()}`,
            {
                method: "GET",
                success: (data) => {
                    if (data.exists) {
                        this.setCustomValidity("The ISBN already exists.");
                        $this.next("span.validity").text("The ISBN already exists");
                    } else
                        this.setCustomValidity("");
                },
                error: (xhr) => {
                }
            }
        )
    });

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

    $("dialog.book button#add-new-author").click(/** @param {jQuery.Event} e */ function (e) {
        let search = $(this).prev("input[type=search]")[0];

        $.ajax(
            `/api/author/name/${search.value}`,
            {
                method: "POST",
                success: (data) => {
                    const option = document.createElement("option");
                    option.value = data.id;
                    option.textContent = data.name;

                    $(this).prev("input[type=search]").val(data.title);
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

<style>
    dialog.book {
        button#remove-author {
            display: none;
        }

        fieldset + fieldset > button#remove-author {
            display: block;
        }

        input:valid + span.validity {
            display: none;
        }
    }
</style>
<?php $dialog->endFragment(); ?>

<?php $dialog->start(); ?>
<fieldset>
    <legend>Book Information</legend>

    <div>
        <label for="title">Title*</label>
        <div style="display: flex">
            <input type="search" id="title" placeholder="Search titles"
                   onchange="fetchWorkOptions.call(this)">
            <button id="add-title" type="button"><i class="fa-solid fa-plus"></i></button>
        </div>
        <select name="work[id]" id="title" style="display: block; width: 100%;" required>
        </select>
    </div>

    <div>
        <label for="isbn">ISBN*</label>
        <input type="text" id="isbn" name="isbn" required
               pattern="\d{13}" minlength="13" maxlength="13">
        <span class="validity"></span>
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4"
                  style="display: block; width: 100%; resize: vertical"></textarea>
    </div>

    <div>
        <label for="format">Format*</label>
        <select name="cover_type" id="format" required>
            <?php foreach (CoverType::cases() as $format): ?>
                <option value="<?= $format->name ?>">
                    <?= $format->title() ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="number-of-pages">Number of Pages*</label>
        <input type="number" name="number_of_pages" id="number-of-pages" min="1">
    </div>

    <div>
        <label for="dimensions">Dimensions</label>
        <input type="text" name="dimensions" id="dimensions">
    </div>

    <div>
        <label for="language">Language</label>
        <input type="text" name="language" id="language">
    </div>

    <div>
        <label for="edition-information">Edition Information</label>
        <input type="text" name="edition_information" id="edition-information">
    </div>
</fieldset>

<fieldset>
    <legend>Publisher</legend>

    <div>

        <label for="publisher">Publisher*</label>
        <input type="text" name="publisher" id="publisher" required>
    </div>

    <div>
        <label for="publication-date">Publication Date*</label>
        <input type="text" name="publication_date" id="publication-date" pattern="[\d\-]+" required>
    </div>
</fieldset>

<fieldset>
    <legend>Authors</legend>

    <fieldset data-author="0">
        <button id="remove-author" type="button" onclick="this.closest('fieldset').remove()">X</button>

        <div>
            <label for="author[0][id]">Author*</label>
            <div style="display: flex;">
                <input type="search" id="author[0][id]" placeholder="Search authors"
                       onchange="fetchAuthorOptions.call(this)">
                <button id="add-new-author" type="button"><i class="fa-solid fa-plus"></i></button>
            </div>
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

    <button id="add-author" type="button">Add author</button>
</fieldset>

<fieldset>
    <legend>Initial Stocks</legend>

    <table>
        <thead>
        <tr>
            <th>Location</th>
            <th>Stock Count</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach (InventoryLocation::cases() as $location): ?>
            <tr>
                <td><label for="initial-stocks[<?= $location->name ?>]"><?= $location->title() ?></label></td>
                <td><input type="number" name="initial_stocks[<?= $location->name ?>]"
                           id="initial-stocks[<?= $location->name ?>]"
                           min="0" step="1" value="0"></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

<fieldset>
    <legend>Initial Pricing</legend>

    <div>
        <label for="initial-price">Initial Unit Price*</label>
        <input type="number" name="initial_price" id="initial-price" min="0.01" step="0.01" required>
    </div>

    <div>
        <label for="initial-cost">Initial Unit Cost</label>
        <input type="number" name="initial_cost" id="initial-cost" min="0.01" step="0.01">
    </div>
</fieldset>
<?= $dialog->end() ?>
