<?php

declare(strict_types=1);


use App\Core\View;

ob_start();
?>
    <main>

        <form id="search">
            <div style="display: flex;">
                <button id="add-book" type="button">+ Add</button>

                <button type="submit">Refresh</button>

                <search style="margin-left: auto">
                    <label>
                        Searching: <input type="text" name="query">
                    </label>
                </search>
            </div>

            <div id="output-table">

            </div>
        </form>

    </main>

    <dialog id="add-book">
        <form method="dialog">

            <div>
                <button type="reset">Cancel</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </dialog>

    <script>
        let $searchForm = $("form#search");

        $searchForm.submit(/** @param {jQuery.Event} e */ (e) => {
            e.preventDefault();

            reloadTable();
        });

        $("search input[name=query]").change(/** @param {jQuery.Event} e */ (e) => {
            reloadTable();
        })

        $("button#add-book").click(/** @param {jQuery.Event} e */ (e) => {
            $("dialog#add-book")[0].showModal();
        });

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
    </script>
<?php

$title = 'Books';
$content = ob_get_clean();

echo View::render(
    'admin/_base.php',
    ['title' => $title, 'content' => $content]
);
