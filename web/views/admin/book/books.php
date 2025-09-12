<?php

declare(strict_types=1);


use App\Core\Template;
use App\Core\View;
use App\Entity\Book\Author\AuthorDefinitionType;
use App\Entity\Product\CoverType;

ob_start();
?>
    <main>

        <form id="search">
            <div style="display: flex; align-items: center">
                <button id="add-book" type="button">+ Add</button>

                <button type="submit">Refresh</button>

                <search style="margin-left: auto; ">
                    <label for="query">Searching:</label>
                    <input type="search" name="query" id="query">
                </search>
            </div>

            <div id="output-table">

            </div>
        </form>

    </main>

    <script>
        let $searchForm = $("form#search");

        $searchForm.submit(/** @param {jQuery.Event} e */ (e) => {
            e.preventDefault();

            reloadTable();
        });

        $("search input[name=query]").change(/** @param {jQuery.Event} e */ (e) => {
            reloadTable();
        })

        function reloadTable() {
            let data = new FormData($searchForm[0]);

            $.ajax(
                '/api/books/search?' + new URLSearchParams(data).toString(),
                {
                    method: 'GET',
                    success: function (data) {
                        $("#output-table").html(data);
                    }
                }
            )
        }

        function gotoPreviousPage() {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = parseInt(input.value) - 1;

            reloadTable();
        }

        function gotoNextPage() {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = parseInt(input.value) + 1;

            reloadTable();
        }

        function gotoPage(page) {
            let input = $searchForm[0].querySelector("input[name=page]");
            input.value = page;

            reloadTable();
        }

        reloadTable();
    </script>

<?php $dialog = new Template(
    '_component/_dialog_form.php',
    ['title' => 'Add Book', 'id' => 'add-book']
);
?>

<?php $dialog->start(); ?>
    <fieldset>
        <legend>Book Information</legend>

        <label>
            Title*
            <input type="search" placeholder="Search titles" onchange="fetchWorkOptions.call(this)">
            <select name="work[id]" style="display: block; width: 100%;" required></select>
        </label>

        <label>
            ISBN*
            <input type="text" name="isbn" required
                   pattern="\d{13}" minlength="13" maxlength="13">
        </label>

        <label>
            Description

            <textarea name="description" rows="4"
                      style="display: block; width: 100%; resize: vertical"></textarea>
        </label>

        <label>
            Format*
            <select name="cover_type" required>
                <?php foreach (CoverType::cases() as $format): ?>
                    <option value="<?= $format->name ?>"><?= $format->title() ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Number of Pages*
            <input type="number" name="number_of_pages" min="1">
        </label>

        <label>
            Dimensions
            <input type="text" name="dimensions">
        </label>

        <label>
            Language
            <input type="text" name="language">
        </label>

        <label>
            Edition Information
            <input type="text" name="edition_information">
        </label>
    </fieldset>

    <fieldset>
        <legend>Publisher</legend>

        <label>
            Publisher*
            <input type="text" name="publisher" required>
        </label>

        <label>
            Publication Date*
            <input type="text" name="publication_date" pattern="[\d\-]+" required>
        </label>
    </fieldset>

    <fieldset>
        <legend>Authors</legend>

        <fieldset data-author="0">
            <button id="remove-author" type="button"
                    onclick="this.closest('fieldset').remove()">X
            </button>

            <label>
                Author*
                <input type="search" placeholder="Search authors" onchange="fetchAuthorOptions.call(this)">
                <select name="authors[0][id]" style="display: block; width: 100%;" required></select>
            </label>

            <label>
                Role*
                <select name="authors[0][type]" required>
                    <?php foreach (AuthorDefinitionType::cases() as $type): ?>
                        <option value="<?= $type->name ?>"><?= $type->title() ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </fieldset>

        <button id="add-author" type="button">Add author</button>
    </fieldset>
<?= $dialog->end() ?>

    <script>
        $("button#add-book").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog#add-book")[0].showModal();
        });

        $("button#add-author").click(/** @param {jQuery.Event} e */ function (e) {
            const clone = $(this).prev("fieldset").clone();

            let order = parseInt(clone[0].dataset.author);
            order += 1;
            clone[0].dataset.author = order.toString();

            clone.find("select[name^=authors][name$='[id]']").attr("name", `authors[${order}][id]`);
            clone.find("select[name^=authors][name$='[type]']").attr("name", `authors[${order}][type]`);

            $(this).before(clone);
        });

        $("dialog button[type=reset]").click(/** @param {jQuery.Event} e */ function (e) {
            this.closest("dialog").close();
        });

        $("dialog#add-book form").submit(/** @param {jQuery.Event} e */ function (e) {
            e.preventDefault();

            $.ajax(
                '/api/book',
                {
                    method: 'POST',
                    data: $(this).serialize(),
                    success: () => {
                        this.closest("dialog").close();
                    },
                    error: (jqXHR, textStatus, errorThrown) => {

                    }
                }
            );
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
        button#remove-author {
            display: none;
        }

        fieldset + fieldset > button#remove-author {
            display: block;
        }
    </style>

<?php

$title = 'Books';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
