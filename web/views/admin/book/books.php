<?php

declare(strict_types=1);

use App\Core\View;

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
            $.ajax(
                '/api/book/search/',
                {
                    method: 'GET',
                    data: $(this).serialize(),
                    headers: {
                        "Accept": "text/html"
                    },
                    success: (data) => {
                        $("#output-table").html(data);
                    }
                }
            );
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

<?= View::render('admin/book/_book_dialog.php', ['title' => 'Add Book']) ?>

    <script>
        $("button#add-book").click(/** @param {jQuery.Event} e */(e) => {
            $("dialog.book")[0].showModal();
        });

        $("dialog.book form").submit(/** @param {jQuery.Event} e */ function (e) {
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
    </script>
<?php

$title = 'Books';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
